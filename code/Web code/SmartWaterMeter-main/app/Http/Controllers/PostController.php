<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\returnSelf;
use function Symfony\Component\VarDumper\Dumper\esc;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cost ($sum)
{      
      $allcost =0;
      switch ($sum){
        case($sum > 126):
            {
                $allcost += (($sum-126) * 3.02); 
                $sum -=($sum-126);
            }
        case ($sum <= 126) && ($sum > 90) :
            {
                $allcost += (($sum-90) * 2.55);  
                $sum -=($sum-90);
            }
        case ($sum <= 90) && ($sum > 72) :
            {
                $allcost += (($sum-72) * 2); 
                $sum -=($sum-72);
            }
        case ($sum <= 72) && ($sum > 54) :
            {
                $allcost += (($sum-54) * 1.57);
                $sum -=($sum-54);
            }
        case ($sum <= 54) && ($sum > 36) :
            {
                $allcost += (($sum-36) * 0.84);
                $sum -=($sum-36);
            }
        case ($sum <= 36) && ($sum > 18) :
            {
                $allcost += (($sum-18) * 0.51);
                $sum -=($sum-18);
            }    
        case ($sum<=18):
            {
             $allcost += $sum * 0.401;  
            }
  }

return $allcost;
}


public function average ($number )
{      
    $y = new PostController ;

      switch ($number){
        case($number >= 7):
            {
                $average=6.10; 
                $averagecost=$y->cost($average);
                break;
            }
        case ($number >= 5) :
            {
                $average=4.19; 
                $averagecost=$y->cost($average);
                break;
            }
        case ($number >= 1) :
            {
               $average=2.26; 
               $averagecost=$y->cost($average);
               break; 
            }
        }
 

        return $finalavg = ['average' => $average , 'cost' =>$averagecost];
}




    public function index(request $request)
    {

        $x = new PostController ;
       
       

        if($request->user()){
        if($request->user()->admin){
            $results=User::all();
            return view ('registerData');
        }
        else 
       {
      
        $infoUser = User::where('id',$request->user()->id )->get();
        
          $results = Post::where('user_id',$request->user()->id )->get();
          $sum= 0 ;
        $count_month= $results[count($results)-1]['created_at']->format('m');
        switch ($count_month%12){
            case($count_month >= 6 ) && ($count_month < 9):
                {
                   $seasons = 4 ;
                   break;
                }
            case ($count_month >= 9) && ($count_month < 12) :
                {
                    $seasons = 2 ;
                    break;
                }
            case ($count_month >= 0 ) && ($count_month < 3 ) :
                {
                    $seasons = 1 ;
                    break;
                }
            case ($count_month >= 3) && ($count_month < 6) :
                {
                    $seasons = 3 ;
                    break;
                }
            }
       $weekly = $results[count($results) - 1]['value'];

        $count= count($results)-1 ;
        $monthly= 0;
        for($i = (count($results)-1) ; $i > (count($results)-5) ; $i--){
                    $monthly +=  $results[$count--]['value'];
                }

       $y = -7.315860848893642 + ($infoUser[0]['familyMembers'] *2.481368) + ($seasons*2.795319)+ ($weekly*4.785608)+($monthly*1.142562);
       $y = round($y.".");
       $costY=round($x->cost($y).".");
       $average=$x->average($infoUser[0]['familyMembers']);
       
   

        $count = (count($results) % 12 );
        for($i= count($results)-1; $i>count($results)-$count-1; $i--){
            $sum+= $results[$i]['value'];
        }


        $monthvalue = $sum;
        $allcost =0;
        $allcost=$x->cost($sum);
           $value=$results[count($results)-1]->value;
           $created_at=$results[count($results)-1]->created_at;
           $updated_at=$results[count($results)-1]->updated_at;

           $cost=$x->cost($value);

           
        $count=0;
           $arr=array();
               for($i = (count($results)-1); $i > (count($results)-6) ; $i--)
              {  
                array_push( $arr,$results[++$count]['value']);
               }
            
               

            
            $posts=['cost' => $cost , 'time' =>$created_at , 'updated_at' =>$updated_at]; 
            $main = ['monthCost' => $allcost ,'sum' => $monthvalue];
            $json =  $arr;
          $prediction  = ['prediction' => $y , 'prediction_cost' => $costY];
          return view('home' ,['posts' => $posts, 'result'=>$results , 'array'=>$json , 'main'=> $main , 'average' =>$average , 'prediction' =>$prediction ]);
           
        
       }}
    
        else {
         return view('auth/login');
        }

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(request $request)
    { 
         
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return View('registerData');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(request $data)
    {
        // User::create([
        //     'id'=>$data['id'],
        //     'admin'=> 0 ,
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        //     'nationalID'=>$data['nationalID'],
        //     'phone'=>$data['phone'],
        //     'familyMembers'=>$data['familyMembers']
        // ]);
       // return view('registerData');
        $singlepost=User::findOrFail($data['id']);
        $singlepost->update(
      [ 'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'nationalID' => $data['nationalID'],
        'phone' => $data['phone'],
        'familyMembers' => $data['familyMembers']]);
          return view('registerData');
    }




    public function updateval( $user_id , $value) 
    {
        $results = Post::where('user_id',$user_id)->get();
        $count = (count($results)-1);
        $record= $results[$count]['created_at']->format('y-m-d');
        $val= $results[$count]['value'];
        str_split($record , 7);
        $yearsDB=  (int)(($record[0]).($record[1]));
        $monthDB=  (int)(($record[3]).($record[4]));
        $dayDB=  (int)(($record[6]).($record[7]));

        $x= Carbon::now()->format('y-m-d');
        str_split($x , 5);
        $years=  (int)(($x[0]).($x[1]));
        $month=  (int)(($x[3]).($x[4]));
        $day=  (int)(($x[6]).($x[7]));
       
        $count_dayDB = ($monthDB*30) +$dayDB;
        $count_day = ($month*30) +$day;
        if(( $count_day - $count_dayDB )  > 7 )
        {
            Post::create([
                'value'=> $value ,
                'user_id' => $user_id 
                ]); 
        }
        else {
            $sum= $val + $value;
            $results[$count] -> update([
               'value' => $sum ,
           ]);
        }


    //     if($years > $yearsDB)
    //     {
    //         Post::create([
    //             'value'=> $value ,
    //             'user_id' => $user_id 
    //             ]);
    //     }
    //     elseif ($month > $monthDB){
    //         Post::create([
    //             'value'=> $value ,
    //             'user_id' => $user_id 
    //             ]);     
    //     }
    //     elseif (($dayDB - $day) > 7 ){
    //         Post::create([
    //             'value'=> $value ,
    //             'user_id' => $user_id 
    //             ]);
    //     }
    //     else {
    //         $sum= $val + $value;
    //          $results[$count] -> update([
    //             'value' => $sum ,
    //         ]);
    // }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function test()
    {
        return view('welcome');
    }
    public function destroy(Post $post)
    {
        //
    }
}
