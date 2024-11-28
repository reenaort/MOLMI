<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Candidate;
use App\Models\courseenrollmentexpense;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function dashboard(Request $request)
    {
        //get total courses
        $data['total_courses'] = Course::where('status', 1)->count();

        //get total candidates
        $data['total_candidates'] = Candidate::count();

        // Sum of expenditure amount
        $data['total_expense'] = courseenrollmentexpense::sum('expenditure_amount');
        $data['total_refund'] = courseenrollmentexpense::sum('refund_amount');
        if($data['total_refund']!=0)
        $data['total_loss'] = $data['total_expense']-$data['total_refund'];
        else
        $data['total_loss']=0;
        
        $totalEnrollments = DB::table('course_enrollment_expenses as e1')
            ->leftJoin('course_enrollment_expenses as e2', function ($join) {
                $join->on('e1.course_id', '=', 'e2.course_id')
                     ->on('e1.can_id', '=', 'e2.can_id')
                     ->where('e2.status', 2);  // Refunds only
            })
            ->where('e1.status', 1)  // Only consider enrollments
            ->whereNull('e2.id')  // Exclude enrollments with refunds
            ->count('e1.id');  // Count total enrollments
        
        // Assign the result to the data array
        $data['total_enrollments'] = $totalEnrollments;
        // $totalExpense = DB::table('course_enrollment_expenses as e1')
        //     ->select(DB::raw('SUM(e1.expenditure_amount) - COALESCE(SUM(e2.refund_amount), 0) as total_expense'))
        //     ->leftJoin('course_enrollment_expenses as e2', function($join) {
        //         $join->on('e1.course_id', '=', 'e2.course_id')
        //              ->on('e1.can_id', '=', 'e2.can_id')
        //              ->where('e2.status', 2);  // Only join with refunds (status = 2)
        //     })
        //     ->where('e1.status', 1)  // Only consider expenditures (status = 1)
        //     ->first();
        // //dd($totalExpense);
        // $data['total_expense'] = $this->formatAmount($totalExpense->total_expense);
        
        // $totalLoss =   DB::table('course_enrollment_expenses as e1')
        //                 ->select('e1.course_id', 'e1.can_id',
        //                     DB::raw('
        //                         SUM(CASE WHEN e1.status = 1 THEN e1.expenditure_amount ELSE 0 END) - 
        //                         COALESCE(SUM(e2.refund_amount), 0) AS total_loss
        //                     ')
        //                 )
        //                 ->leftJoin('course_enrollment_expenses as e2', function($join) {
        //                     $join->on('e1.course_id', '=', 'e2.course_id')
        //                          ->on('e1.can_id', '=', 'e2.can_id')
        //                          ->where('e2.status', 2);
        //                 })
        //                 ->where('e1.status', 1)  // Only consider expenditures from status = 1
        //                 ->groupBy('e1.course_id', 'e1.can_id')
        //                 ->havingRaw('SUM(e2.refund_amount) > 0')  // Exclude if no refund exists
        //                 ->get();
        // $grandTotalLoss = $totalLoss->sum('total_loss');
        // $data['total_loss']=  $this->formatAmount($grandTotalLoss);

        //last 6 months enrollment count
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $currentMonth = Carbon::now()->startOfMonth();
        
        $monthlyEnrollments = DB::table(DB::raw('(SELECT generate_series(
            DATE \'' . $sixMonthsAgo->format('Y-m-d') . '\', 
            DATE \'' . $currentMonth->format('Y-m-d') . '\', 
            INTERVAL \'1 month\'
        ) AS month) as months'))
            ->leftJoin('course_enrollment_expenses as e1', function ($join) {
                $join->on(DB::raw('DATE_TRUNC(\'month\', e1.created_at)'), '=', 'months.month')
                     ->where('e1.status', 1);
            })
            ->leftJoin('course_enrollment_expenses as e2', function ($join) {
                $join->on('e1.course_id', '=', 'e2.course_id')
                     ->on('e1.can_id', '=', 'e2.can_id')
                     ->where('e2.status', 2);  // Refunds only
            })
            ->whereNull('e2.id')  // Only consider enrollments without refunds
            ->select(
                DB::raw('TO_CHAR(months.month, \'Mon-YY\') as month'),  // Format month as Jan-24
                DB::raw('COUNT(e1.id) as enrollment_count')
            )
            ->groupBy('months.month')
            ->orderBy('months.month', 'asc')
            ->get();
        //dd($monthlyEnrollments);
    //     $enroll_count = DB::select("
    //     WITH months AS (
    //         -- Generate a sequence of the last 6 months
    //         SELECT
    //             to_char(date_trunc('month', CURRENT_DATE) - INTERVAL '5 months' + INTERVAL '1 month' * generate_series(0, 5), 'YYYY-MM') AS month
    //     )
    //     SELECT
    //         months.month,  -- Extracted months
    //         COALESCE(COUNT(DISTINCT cee.can_id), 0) AS candidate_count  -- Count distinct candidates, default to 0 if none found
    //     FROM
    //         months  -- Use the generated months as the base
    //     LEFT JOIN (
    //         -- Subquery to get the latest entries per candidate-course in the last 6 months
    //         SELECT
    //             TO_CHAR(cee.created_at, 'YYYY-MM') AS month,
    //             cee.can_id
    //         FROM
    //             course_enrollment_expenses AS cee
    //         JOIN (
    //             SELECT
    //                 MAX(id) AS latest_entry_id
    //             FROM
    //                 course_enrollment_expenses
    //             WHERE
    //                 status = 1
    //                 AND created_at >= CURRENT_DATE - INTERVAL '6 months'
    //             GROUP BY
    //                 can_id, course_id, TO_CHAR(created_at, 'YYYY-MM')
    //         ) AS latest_entries ON cee.id = latest_entries.latest_entry_id
    //         WHERE
    //             cee.created_at >= CURRENT_DATE - INTERVAL '6 months'
    //     ) AS cee ON months.month = cee.month  -- Left join to include all months even with no data
    //     GROUP BY
    //         months.month
    //     ORDER BY
    //         months.month ASC;  -- Order by month
    // ");

        $monthsArray = [];
        $candidateCounts = [];

        // Process the results to fill the arrays
        foreach ($monthlyEnrollments as $row) {
            //$date = Carbon::createFromFormat('Y-m', $row->month);
            $monthsArray[] = $row->month;    // Get month
            $candidateCounts[] = (int)$row->enrollment_count; // Get candidate count as an integer
        }
        $data['months'] = $monthsArray;
        $data['candidate_count'] = $candidateCounts;
        //dd($data);
        return view('pages.dashboard', $data);
    }
    // Function to format amounts in PHP
    function formatAmount($amount)
    {
        if ($amount >= 1000000) {
            return number_format($amount / 1000000, 1) . 'm'; // Format to millions
        } elseif ($amount >= 10000) {
            return number_format($amount / 1000, 1) . 'k'; // Format to thousands
        } else {
            return $amount; // Return the number as is
        }
    }
}