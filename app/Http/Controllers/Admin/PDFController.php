<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \setasign\Fpdf\Fpdf;
use \setasign\Fpdi\Fpdi;
use App\Models\Domains;
use App\Models\DomainScanScore;
use DB;

class PDFController extends Controller
{
    public function showPageNumber($pdf, $importPage='', $hidePageNumber='') {
        $pdf->AddPage();

        if($importPage) {
            $tplIdx = $pdf->importPage($importPage);
            $pdf->useTemplate($tplIdx);
        }

        /*if($hidePageNumber == '') {
            $pageNo = sprintf("%02d", $pdf->PageNo());
            $font = 'Helvetica';
            $pdf->SetFont($font, '', 16);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(190, 275); 
            $pdf->Write(0, $pageNo);
        }*/
    }

    // private $fpdf;
    public function createPDF(Request $request,$id)
    {
    	$domainId = decrypt($id);

        $domaindata = Domains::select('domain_name', 'average_score', DB::raw('DATE_FORMAT(ds_domains.last_scan_date, "%d-%m-%Y") as last_scan_date'), DB::raw('DATE_FORMAT(ds_domains.created_at, "%d-%m-%Y") as generated_date'))
            ->where('id',$domainId)
            ->first();

        // dd($domaindata);

        $average_score = $domaindata['average_score'];
        $domainName = strtoupper($domaindata['domain_name']);
        $getInfoByScore = getRatingInfoByScore($average_score);
        $domainRating = strtoupper($getInfoByScore['grade']);
        $domainRatingValue = ucfirst(trim($getInfoByScore['performance']));
        $domainRatingMessage = trim($getInfoByScore['message']); 

        // dd($domaindata);

        $domaincategorydata = Domains::select('ds_probs_category.*', 'ds_probs_category.id as category_id','ds_score_by_category.average_score')
            ->join('ds_score_by_category','ds_score_by_category.domain_id','=','ds_domains.id')
            ->join('ds_probs_category','ds_probs_category.id','=','ds_score_by_category.probs_category_id')
            ->where('ds_domains.id',$domainId)
            ->where('ds_probs_category.status', 'Active')
            ->orderBy('ds_probs_category.id', 'ASC')
            // ->groupBy('ds_probs_category.id')
            ->get();

        // initiate FPDI
        // $pdf = new Fpdi();
        // $pdf = new PDF_WITH_HTML();
        $pdf = new PDF_MC_Table();
        // set the source file
        // $pdf->setSourceFile("./PDF/TRUSTDOMFInal.pdf");
        $pdf->setSourceFile("./PDF/TRUSTDOMFInalDev.pdf");

        $xAxis = 20;
        $font = 'Helvetica';
        $importPage = 1;

        // add a page
        $this->showPageNumber($pdf, $importPage, 'yes');

        // now write some text above the imported page
        $pdf->SetFont($font, '', 16);
        $pdf->SetTextColor(31, 169, 208);
        $pdf->SetXY($xAxis, 256);
        $pdf->Write(0, $domainName);

        $pdf->SetFont($font, '', 13);
        $pdf->SetTextColor(139, 139, 139);
        $pdf->SetXY($xAxis, 268);
        $pdf->Write(0, 'Last Scan Date: '.$domaindata['last_scan_date']);

        $pdf->SetXY($xAxis, 276);
        $pdf->Write(0, 'Report Generated: '.date('d-m-Y'));

        //*
        // add a page
        $importPage++;
        $this->showPageNumber($pdf, $importPage);

        // add a page
        $importPage++;
        $this->showPageNumber($pdf, $importPage);

        // add a page
        $importPage++;
        $this->showPageNumber($pdf, $importPage);

        // add a page
        $importPage++;
        $this->showPageNumber($pdf, $importPage);

        // add a page
        $importPage++;
        $this->showPageNumber($pdf, $importPage);

        // add a page
        $importPage++;
        $this->showPageNumber($pdf, $importPage);

        // add a page
        $importPage++;
        $this->showPageNumber($pdf, $importPage);
        //*/

        $importPage = 9;
        $this->showPageNumber($pdf, $importPage);

        ## over all rating
        $speedMeterImage = speedMeterImage($average_score);
        $pdf->Image($speedMeterImage, 130, 20, 60, 38, 'PNG');

        if($domainRatingMessage) {
            $pdf->SetFont($font, '', 9);
            $pdf->SetTextColor(76,76,76);

            $widthArr = Array(185);
            $pdf->SetXY($xAxis, 90);
            $pdf->SetWidths($widthArr); 
            $pdf->SetLineHeight(3);
            $pdf->SetAligns(Array('')); 
            $pdf->SetDrawColor(255,255,255);
            // $pdf->Cell($widthArr[0],10,$ratingMessage,0,0,'',true);

            $pdf->Row(
                Array($domainRatingMessage)
            );
        }

        ## doamin name and rating value
        $yAxis = 60;

        $pdf->SetFont($font, 'B', 24);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetXY($xAxis+3, $yAxis+3);
        $pdf->Write(0, $domainRating);

        $pdf->SetFont($font, '', 14);
        $pdf->SetTextColor(31, 169, 208);
        $pdf->SetXY($xAxis+15, $yAxis);
        $pdf->Write(0, $domainName);

        $pdf->SetFont($font, '', 11);
        $pdf->SetTextColor(76,76,76);
        $pdf->SetXY($xAxis+15, $yAxis+7);
        $pdf->Write(0, $domainRatingValue);

        ## show categories information
        $pdf->SetFont($font, '', 12);
        $pdf->SetTextColor(76,76,76);

        $yAxis = 100;
        // $catIcon = asset("img/grey-company-icon.png");
        foreach ($domaincategorydata as $key => $value) {
            $getRating = getRating($value['average_score']);
            $getRatingIcon = asset("img/rating-".trim($getRating).".png");
            # code...
            $yAxis = $yAxis + 20;
            // $pdf->Image($catIcon, $xAxis+2, ($yAxis-6), 9, 9, 'PNG');

            $pdf->SetFont($font, '', 12);
            $pdf->SetXY($xAxis + 15, $yAxis);
            $pdf->Write(0, $value['category_name']);

            $pdf->Image($getRatingIcon, $xAxis+82, ($yAxis-5), 11, 11, 'PNG');

            $widthArr = Array(80);
            $pdf->SetFont($font, '', 9);
            $pdf->SetXY($xAxis+100, $yAxis - 5);
            $pdf->SetWidths($widthArr); 
            $pdf->SetLineHeight(1);
            $pdf->SetAligns(Array('')); 
            $pdf->SetDrawColor(255,255,255);

            $ratingMessage = $value[strtolower('grade_'.$getRating)];            
            $pdf->Row(
                Array($ratingMessage)
            );
        }

        $yAxis = $yAxis + 30;

        // $ratingArr = array('A','B','C','B','E');

        // $xAxisRating = $xAxis + 2;
        // $pdf->SetFont($font, '', 10);
        // foreach($ratingArr as $key=>$rating) {
        //     $getRatingIcon = asset("img/rating-".$rating.".png");
        //     $pdf->SetXY($xAxisRating, $yAxis);
        //     $pdf->Image($getRatingIcon, $xAxisRating, ($yAxis-5), 9, 9, 'PNG');

        //     $xAxisRating = $xAxisRating + 10;
        //     $pdf->SetXY($xAxisRating, $yAxis);
        //     $pdf->Write(0, getRatingValue($rating));

        //     $xAxisRating = $xAxisRating + 20;
        // }


        ## subcategories data
        $importPage = 10;
        $this->showPageNumber($pdf, $importPage);

        ## doamin name and rating value
        $yAxis = 125;
        $pdf->SetFont($font, 'B', 24);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetXY($xAxis+4, $yAxis+4);
        $pdf->Write(0, $domainRating);

        $pdf->SetFont($font, '', 14);
        $pdf->SetTextColor(31, 169, 208);
        $pdf->SetXY($xAxis+15, $yAxis);
        $pdf->Write(0, $domainName);

        $pdf->SetFont($font, '', 11);
        $pdf->SetTextColor(76,76,76);
        $pdf->SetXY($xAxis+15, $yAxis+7);
        $pdf->Write(0, $domainRatingValue);

        ## sub categories information
        $pdf->SetFont($font, '', 13);
        $pdf->SetTextColor(139, 139, 139);
        $xAxis = $xAxis - 10;
        $yAxis = 20;
        
        foreach ($domaincategorydata as $key => $value) {
            // code to get subcategories data from ds_domain_scan_score on basis of the category ID and domain ID.
            $domainsubcategories = DomainScanScore::select('ds_probs_sub_category.*', 'ds_domain_scan_score.score','ds_domain_scan_score.status','ds_domain_scan_score.message')
                ->join('ds_probs_sub_category','ds_probs_sub_category.id','=','ds_domain_scan_score.probs_sub_category_id')
                ->where('ds_domain_scan_score.domain_id', $domainId)
                ->where('ds_probs_sub_category.category_id', $value['category_id'])
                ->orderBy('ds_probs_sub_category.id', 'ASC')
                ->get();

            if($key=='0'){
                $yAxis = 144;
            }else{
                $this->showPageNumber($pdf);
                $yAxis = 20;
            }

            $pdf->SetFont($font, '', 14);
            $pdf->SetXY($xAxis, $yAxis);
            $pdf->SetTextColor(139, 139, 139);
            $pdf->Write(0, $value['category_name']);

            if(count($domainsubcategories) > 0) {
                $yAxis = $yAxis + 5;
                $pdf->SetXY($xAxis, $yAxis);
                $pdf->Write(0, '' );

                $pdf->SetFont($font, '', 10);
                $pdf->SetFillColor(8, 169, 208); 
                $pdf->SetTextColor(255, 255, 255);
                $pdf->SetDrawColor(233,233,233);

                $widthArr = Array(26,18,66,80); // total sum must be 190
                $pdf->SetWidths($widthArr); 
                $pdf->SetLineHeight(5);
                $pdf->SetAligns(Array('','','',''));

                $pdf->Cell($widthArr[0],10,'Risk Zone Probe',1,0,'',true);  
                $pdf->Cell($widthArr[1],10,'Result',1,0,'',true); 
                $pdf->Cell($widthArr[2],10,'Result Message',1,0,'',true);
                $pdf->Cell($widthArr[3],10,'Remediation',1,1,'',true); 
                //add a new line
                // $pdf->Ln();

                foreach ($domainsubcategories as $k => $subvalue) {
                    $pdf->SetTextColor(76,76,76);
                    $message = '';
                    if(strtolower($subvalue['status']) == 'pass'){
                        $message = $subvalue['pass_message'];
                    }
                    else{
                        $message = $subvalue['fail_message'];
                    }
                    if(strtolower($subvalue['status']) == 'pass'){
                        $Remediation = 'N/A';
                    }
                    else{
                        $Remediation =  $subvalue['remediation_message'];
                        if($subvalue['message']) {
                            $pdf->SetFont($font, 'B', 10);
                            $Remediation .= "\n".$subvalue['message'];
                            $pdf->SetFont($font, '', 10);
                        }
                    }

                    $pdf->Row(Array(
                        $subvalue['sub_category_display_name'], 
                        $subvalue['status'], 
                        $message, 
                        $Remediation
                    ));
                }

            }
            else{
                $yAxis = $yAxis + 9;
                $pdf->SetFont($font, '', 10);
                $pdf->SetTextColor(76,76,76);
                $pdf->SetXY($xAxis, $yAxis);
                $pdf->Write(0, 'No data found.');
            }
        }

       
        
        // add a page
        $importPage = 11;
        $this->showPageNumber($pdf, $importPage);

        // add a page
        $importPage++;
        $this->showPageNumber($pdf, $importPage, 'yes');

        $pdf->Output('I', 'doamin-report.pdf');
    }
}

class PDF_MC_Table extends Fpdi {

    // variable to store widths and aligns of cells, and line height
    var $widths;
    var $aligns;
    var $lineHeight;

    function Footer()
    {
        $pageNo = $this->PageNo();
        // $this->AliasNbPages();

        if($pageNo != 1 && ($this->PageNo() != '{nb}')) {
            $this->SetXY(-24, -20);
            $this->SetFillColor(37,169,209); 
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Arial','',14);
            $this->Cell(24,9, sprintf("%02d", $pageNo),0,0,'C', true);
        }
    }

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
    function Row($data)
    {
        // number of line
        $nb=0;

        // loop each data to find out greatest line number in a row.
        for($i=0;$i<count($data);$i++){
            // NbLines will calculate how many lines needed to display text wrapped in specified width.
            // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        }
        
        //multiply number of line with line height. This will be the height of current row
        $h=$this->lineHeight * $nb;

        //Issue a page break first if needed
        $this->CheckPageBreak($h);

        //Draw the cells of current row
        for($i=0;$i<count($data);$i++)
        {
            // width of the current col
            $w=$this->widths[$i];
            // alignment of the current col. if unset, make it left.
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->Rect($x,$y,$w,$h);
            //Print the text
            $this->MultiCell($w,5,$data[$i],0,$a);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w,$txt)
    {
        //calculate the number of lines a MultiCell of width w will take
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb)
        {
            $c=$s[$i];
            if($c=="\n")
            {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
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