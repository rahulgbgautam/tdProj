<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \setasign\Fpdf\Fpdf;
use \setasign\Fpdi\Fpdi;
use App\Models\Domains;
use App\Models\subscription;
use App\Models\DomainScanScore;
use DB;
use Carbon\Carbon;

class InvoicePdfController extends Controller
{
    public function showPageNumber($pdf, $importPage='', $hidePageNumber='') {
        $pdf->AddPage();

        if($importPage) {
            $tplIdx = $pdf->importPage($importPage);
            $pdf->useTemplate($tplIdx);
        }
    }

    // private $fpdf;
    public function createPDF(Request $request,$id)
    {
        $subscriptionId = decrypt($id);
        $data = subscription::select('subscriptions.id','subscriptions.transaction_number','subscriptions.created_at','subscriptions.subscription_type','subscriptions.quantity','subscriptions.price','subscriptions.total_amount','subscriptions.expire_date','subscriptions.promo_code','subscriptions.discount','subscriptions.paid_amount','subscriptions.expire_date','users.name','users.email')
            // ->join('ds_domain_users','ds_domain_users.subscription_id','=','subscriptions.id')
            ->join('users','users.id','=','subscriptions.user_id')
            ->where('subscriptions.id',$subscriptionId)
            ->first();

        // initiate FPDI
        // $pdf = new Fpdi();
        // $pdf = new PDF_WITH_HTML();
        $pdf = new PDF_MC_Table();
        // set the source file
        // $pdf->setSourceFile("./PDF/TRUSTDOMFInal.pdf");
        $pdf->setSourceFile("./PDF/TrustDominvoice.pdf");

        $xAxis = 20;
        $font = 'Helvetica';
        $importPage = 1;

        // add a page
        $this->showPageNumber($pdf, $importPage, 'yes');

        // now write some text above the imported page
        $yAxis = 100;
        $pdf->SetFont($font, '', 25);
        $pdf->SetTextColor(31, 169, 200);
        $pdf->SetXY($xAxis+120, $yAxis-60);
        $pdf->Write(0, '');

        $date = Carbon::now('Asia/Kolkata');
        $formatedDate = $date->format('d/m/Y H:iA');
        // $date = Carbon::now('Asia/Kolkata')->toDateTimeString();
        $pdf->SetFont($font, '', 13);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+27, $yAxis-57);
        // $pdf->Write(0, 'Date: '.date('d-m-Y/H:i:s'));
        $pdf->Write(0, ' '.$formatedDate);

        $pdf->SetFont($font, '', 13);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+27, $yAxis-38);
        $pdf->Write(0, ' '.ucfirst($data->name));

        $pdf->SetFont($font, '', 13);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+27, $yAxis-30);
        $pdf->Write(0, ' '.$data->email);

        $pdf->SetFont($font, '', 13);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+28, $yAxis-65);
        $pdf->Write(0, ''.generateInvoiceNumber($data->id,$data->created_at));

        $pdf->SetFont($font, '', 13);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+28, $yAxis-48);
        $pdf->Write(0, ''.$data->transaction_number);

        $pdf->SetFont($font, '', 12);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis-11, $yAxis-0);
        if($data->subscription_type == "Membership"){
           $pdf->Write(0, ''."Purchased Subscription");
       }elseif($data->subscription_type == "Yearly"){
           $pdf->Write(0, ''."Purchased Yearly Credits");
       }
       else
           $pdf->Write(0, ''."Purchased Monthly Credits");
        // $pdf->Write(0, ''.$data->subscription_type);

        $pdf->SetFont($font, '', 12);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+85, $yAxis-0);
        $pdf->Write(0, ' '.$data->quantity);

        $pdf->SetFont($font, '', 12);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+122, $yAxis-0);
        $pdf->Write(0, '$'.number_format($data->price,2));

        $pdf->SetFont($font, '', 12);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+165, $yAxis-0);
        $pdf->Write(0, '$'.number_format($data->total_amount,2));

        $pdf->SetFont($font, '', 15);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+137, $yAxis+44);
        $pdf->Write(0, '$'.number_format($data->total_amount,2));

        $pdf->SetFont($font, '', 15);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+136, $yAxis+56);
        $pdf->Write(0, ' '.(($data->promo_code)?($data->promo_code):'-'));

        $pdf->SetFont($font, '', 15);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+137, $yAxis+69);
        $pdf->Write(0, '$'.number_format($data->discount,2));

        $pdf->SetFont($font, '', 15);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis+137, $yAxis+80);
        $pdf->Write(0, '$'.number_format($data->paid_amount,2));

        // $pdf->SetXY($xAxis+0, $yAxis-10);
        // $pdf->SetFont($font,'',13);
        // $pdf->Cell(55,10,$data->domain_name,1,0,'L',0);
        // $pdf->Cell(50,10,$data->subscription_type,1,0,'L',0);
        // $pdf->Cell(25,10,$data->quantity,1,0,'L',0);
        // $pdf->Cell(20,10,'$'.number_format($data->price,2),1,0,'R',0);

        // $pdf->SetXY($xAxis+0, $yAxis);
        // $pdf->SetFont($font,'B',13);
        // $pdf->Cell(130,10,'Total',1,0,'L',0);
        // $pdf->Cell(20,10,'$'.number_format($data->total_amount,2),1,0,'R',0);

        // $pdf->SetXY($xAxis, 276);
        // $pdf->Write(0, '');

        $pdf->Output('I', 'invoice-report.pdf');
    }
}

class PDF_MC_Table extends Fpdi {

    // variable to store widths and aligns of cells, and line height
    var $widths;
    var $aligns;
    var $lineHeight;

    //Set the array of column widths
    function SetWidths($w){
        $this->widths=$w;
    }

    //Set the array of column alignments
    function SetAligns($a){
        $this->aligns=$a;
    }

    //Set line height
    function SetLineHeight($h){
        $this->lineHeight=$h;
    }

    //Calculate the height of the row

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }
    
}

/*
class PDF_WITH_HTML extends Fpdi
{
    //variables of html parser
    protected $B;
    protected $I;
    protected $U;
    protected $HREF;
    protected $fontList;
    protected $issetfont;
    protected $issetcolor;

    function __construct($orientation='P', $unit='mm', $format='A4')
    {
        //Call parent constructor
        parent::__construct($orientation,$unit,$format);

        //Initialization
        $this->B=0;
        $this->I=0;
        $this->U=0;
        $this->HREF='';

        $this->tableborder=0;
        $this->tdbegin=false;
        $this->tdwidth=0;
        $this->tdheight=0;
        $this->tdalign="L";
        $this->tdbgcolor=false;

        $this->oldx=0;
        $this->oldy=0;

        $this->fontlist=array("arial","times","courier","helvetica","symbol");
        $this->issetfont=false;
        $this->issetcolor=false;
    }

    //////////////////////////////////////
    //html parser
    function WriteHTML($html)
    {
        $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote><hr><td><tr><table><sup>"); //remove all unsupported tags
        $html=str_replace("\n",'',$html); //replace carriage returns with spaces
        $html=str_replace("\t",'',$html); //replace carriage returns with spaces
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //explode the string
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                elseif($this->tdbegin) {
                    if(trim($e)!='' && $e!="&nbsp;") {
                        $this->Cell($this->tdwidth,$this->tdheight,$e,$this->tableborder,'',$this->tdalign,$this->tdbgcolor);
                    }
                    elseif($e=="&nbsp;") {
                        $this->Cell($this->tdwidth,$this->tdheight,'',$this->tableborder,'',$this->tdalign,$this->tdbgcolor);
                    }
                }
                else
                    $this->Write(5,stripslashes(($e)));
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract attributes
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $attr=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        //Opening tag
        switch($tag){

            case 'SUP':
                if( !empty($attr['SUP']) ) {    
                    //Set current font to 6pt     
                    $this->SetFont('','',6);
                    //Start 125cm plus width of cell to the right of left margin         
                    //Superscript "1" 
                    $this->Cell(2,2,$attr['SUP'],0,0,'L');
                }
                break;

            case 'TABLE': // TABLE-BEGIN
                if( !empty($attr['BORDER']) ) $this->tableborder=$attr['BORDER'];
                else $this->tableborder=0;
                break;
            case 'TR': //TR-BEGIN
                break;
            case 'TD': // TD-BEGIN
                if( !empty($attr['WIDTH']) ) $this->tdwidth=($attr['WIDTH']/4);
                else $this->tdwidth=40; // Set to your own width if you need bigger fixed cells
                if( !empty($attr['HEIGHT']) ) $this->tdheight=($attr['HEIGHT']/6);
                else $this->tdheight=6; // Set to your own height if you need bigger fixed cells
                if( !empty($attr['ALIGN']) ) {
                    $align=$attr['ALIGN'];        
                    if($align=='LEFT') $this->tdalign='L';
                    if($align=='CENTER') $this->tdalign='C';
                    if($align=='RIGHT') $this->tdalign='R';
                }
                else $this->tdalign='L'; // Set to your own
                if( !empty($attr['BGCOLOR']) ) {
                    $coul=hex2dec($attr['BGCOLOR']);
                        $this->SetFillColor($coul['R'],$coul['G'],$coul['B']);
                        $this->tdbgcolor=true;
                    }
                $this->tdbegin=true;
                break;

            case 'HR':
                if( !empty($attr['WIDTH']) )
                    $Width = $attr['WIDTH'];
                else
                    $Width = $this->w - $this->lMargin-$this->rMargin;
                $x = $this->GetX();
                $y = $this->GetY();
                $this->SetLineWidth(0.2);
                $this->Line($x,$y,$x+$Width,$y);
                $this->SetLineWidth(0.2);
                $this->Ln(1);
                break;
            case 'STRONG':
                $this->SetStyle('B',true);
                break;
            case 'EM':
                $this->SetStyle('I',true);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->SetStyle($tag,true);
                break;
            case 'A':
                $this->HREF=$attr['HREF'];
                break;
            case 'IMG':
                if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                    if(!isset($attr['WIDTH']))
                        $attr['WIDTH'] = 0;
                    if(!isset($attr['HEIGHT']))
                        $attr['HEIGHT'] = 0;
                    $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
                }
                break;
            case 'BLOCKQUOTE':
            case 'BR':
                $this->Ln(5);
                break;
            case 'P':
                $this->Ln(10);
                break;
            case 'FONT':
                if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                    $coul=hex2dec($attr['COLOR']);
                    $this->SetTextColor($coul['R'],$coul['G'],$coul['B']);
                    $this->issetcolor=true;
                }
                if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->issetfont=true;
                }
                if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist) && isset($attr['SIZE']) && $attr['SIZE']!='') {
                    $this->SetFont(strtolower($attr['FACE']),'',$attr['SIZE']);
                    $this->issetfont=true;
                }
                break;
        }
    }

    function CloseTag($tag)
    {
        //Closing tag
        if($tag=='SUP') {
        }

        if($tag=='TD') { // TD-END
            $this->tdbegin=false;
            $this->tdwidth=0;
            $this->tdheight=0;
            $this->tdalign="L";
            $this->tdbgcolor=false;
        }
        if($tag=='TR') { // TR-END
            $this->Ln();
        }
        if($tag=='TABLE') { // TABLE-END
            $this->tableborder=0;
        }

        if($tag=='STRONG')
            $tag='B';
        if($tag=='EM')
            $tag='I';
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='FONT'){
            if ($this->issetcolor==true) {
                $this->SetTextColor(0);
            }
            if ($this->issetfont) {
                $this->SetFont('arial');
                $this->issetfont=false;
            }
        }
    }

    function SetStyle($tag, $enable)
    {
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s) {
            if($this->$s>0)
                $style.=$s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt)
    {
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}
*/
