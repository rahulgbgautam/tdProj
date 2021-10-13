<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProbsCategory;
use App\Models\subscription;
use App\Models\Domains;
use App\Models\User;
use App\Banner;
use Session;
use Hash;
use Auth;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewDashboard(Request $request)
    {
        ### code to store is user subscribed
        $userid=Auth::id(); 
        $subscription = new subscription();     
        $subscription->getCurrentSubscription($userid); 

        $data['dataBytype'][1] = $this->getDashboardByType(1);
        $data['dataBytype'][2] = $this->getDashboardByType(2);

        $data['ratings'] = getRatings();

        return view('front.dashboard', $data);
    }


    public function getDashboardByType($type)
    {
        $user_id = auth()->user()->id;
        ## code to get information for brands tab
        $domainsAvg = Domains::select(DB::raw('AVG(average_score) as average_score'))
            ->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
            ->where('ds_domain_users.user_id',$user_id)
            ->where('ds_domain_users.type', $type)
            ->where('ds_domains.status', 'Active')
            ->first();

        $domains = Domains::select('ds_domains.*','ds_domain_users.expiry_date')
            ->join('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
            ->where('ds_domain_users.user_id',$user_id)
            ->where('ds_domain_users.type', $type)
            ->where('ds_domains.status', 'Active')
            ->where('ds_domain_users.expiry_date','>',date('Y-m-d'))
            ->take(6)
            ->orderBy('ds_domains.average_score','desc')
            ->get();

        $query = ProbsCategory::select('ds_probs_category.category_name', DB::raw('AVG(ds_score_by_category.average_score) as average_score'))
            ->leftjoin('ds_score_by_category','ds_probs_category.id','=','ds_score_by_category.probs_category_id')
            ->leftjoin('ds_domains','ds_score_by_category.domain_id','=','ds_domains.id')
            ->leftjoin('ds_domain_users','ds_domain_users.domain_id','=','ds_domains.id')
            ->where('ds_probs_category.status',"Active");

            if(count($domains) > 0){  
                $query->where('ds_domains.status', 'Active');
                $query->where('ds_domain_users.user_id',$user_id);
                $query->where('ds_domain_users.type', $type);
            }
            $query->where('ds_domains.status', 'Active');
            $query->where('ds_probs_category.status', 'Active');
            $query->orderBy('ds_probs_category.id', 'ASC');
            $query->groupBy('ds_probs_category.id');
            $scores = $query->get();  

        $domainsRating = '';
        if(count($domains) < 1){
            $domainsRating = '-';
        }

        if($type == 1) {
            $title = "My Domains";
            $subTitle = "My Domain Risk Zone Average Rating";
            $chartTitle = "My Domain Average Rating";
        }
        else{
            $title = "Vendor Domains";
            $subTitle = "Vendor Domain Risk Zone Average Rating";
            $chartTitle = "Vendor Domain Average Rating";
        }

        $average_score = 0;
        if(isset($domainsAvg->average_score)) {
            $average_score = $domainsAvg->average_score;
        }

        $getInfoByScore  = getRatingInfoByScore($average_score);
        $rating          = $getInfoByScore['grade'];
        $domainsAvgImg   = speedMeterImage($average_score);
        $domainsAvgMsg   = $getInfoByScore['message'];

        $data['title']         = $title;
        $data['subTitle']      = $subTitle;
        $data['chartTitle']    = $chartTitle;
        $data['domains']       = $domains;
        $data['domainsRating'] = $domainsRating;
        $data['scores']        = $scores;

        ## code to get tranding chart data
        $chartData = getTrandingChartData($user_id, '', $type);
        $chartData['type'] = $type;       
        $chartData['speedMeterImage'] = $domainsAvgImg;       
        $chartData['domainRatingMessage'] = trim($domainsAvgMsg);
        
        $data['chart'] = $chartData;

        return $data;
    }
}
