<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $host        = "host=127.0.0.1";
$port        = "port=5432";
$dbname      = "dbname = iroproject";
$credentials = "user = postgres password=Root@2022";

$db = pg_connect( "$host $port $dbname $credentials"  );
















    



//$date=date_create("2013-03-15");

//get the date difference

// $sql9 =<<<EOF
//              SELECT "EXPECTED_COMPLETION_DATE" FROM public.iro_case_mst WHERE "STATUS"='7';


//         EOF;



//         $result9 = pg_query($db, $sql9);
// while ($row9 = pg_fetch_array($result9)) 
//        {

        
//         $EXPECTED_COMPLETION_DATE= $row9['EXPECTED_COMPLETION_DATE'];
//         $today9 =date("Y-m-d");

//         $date9 = date_format($EXPECTED_COMPLETION_DATE,"Y-m-d");

//         $date1=date_create($date9);
//         $date2=date_create($today9);
//         $diff=date_diff($date1,$date2);

//        }

     




//reminder and  invalidating cronjob

$sql =<<<EOF
             SELECT "RESPONSE_ON","CASE_ID","OFFICER_PNO" FROM public.iro_case_mst WHERE "STATUS"='7';


        EOF;
$result2 = pg_query($db, $sql);
while ($row2 = pg_fetch_array($result2)) 
       {

     
$CASE_ID = $row2['CASE_ID'];
$OFFICER_PNO= $row2['OFFICER_PNO'];
$responsedate = $row2['RESPONSE_ON'];






$sql7 =<<<EOF
             SELECT "EMAIL_ADDRESS" FROM public.iro_user_mst WHERE "USER_NAME"='$OFFICER_PNO';


        EOF;
        $result7 = pg_query($db, $sql7);
        $row7= pg_fetch_array($result7);
        $email_adress = $row7['EMAIL_ADDRESS'];

        


  $newDate = date('Y-m-d', strtotime($responsedate));

 //echo $new_date = date('Y-m-d', strtotime($responsedate));  
  $today1 =date("Y-m-d");
  $today =date("Y-m-d");

  $date7=date_create($responsedate);
 date_sub($date7,date_interval_create_from_date_string("4 days"));
 $date7 = date_format($date7,"Y-m-d");



 //$reminder_date=strtotime( $date7);
$response=strtotime($newDate);
  $today=strtotime($today);

if($today1==$date7)
{

      
       $subject = "IRO Reminder";
        $mes="Objection of case id:  ".$CASE_ID."  will be automatically invalidated in 4days time!!!";
       $headers = 'From:DTD@KRA.GO.KE';
           mail($email_adress,$subject,$mes,$headers);
       
       

}

if($today>$response)
{
 $sql2 =<<<EOF

              UPDATE public.iro_case_mst SET "STATUS"='-3' where "CASE_ID"='$CASE_ID';
           EOF;
                  $ret = pg_query($db, $sql2);
                  if(!$ret) {
                     echo pg_last_error($db);
                  } else {
                    // $stidu = oci_parse($conn3, " UPDATE IRO_MAPPING SET OFFICER='TYTY'");
                      //   oci_execute($stidu);                 }


}



} 
       }
    }
}
