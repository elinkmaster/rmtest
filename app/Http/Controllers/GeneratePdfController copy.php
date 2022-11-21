<?php

namespace App\Http\Controllers;

use App\Helpers\NumberFormatterHelper;
use App\Helpers\UtilityHelper;
use App\Models\Author;
use App\Models\Book;
use App\Models\EbookTransaction;
use App\Models\PodTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumberFormatter;
use PDF;

class GeneratePdfController extends Controller
{
    
    public function generate(Request $request)
    {
        $request->validate([
            'author' => 'required',
            
            'book' => 'required',
            'fromYear' => 'required',
            'fromMonth' => 'required',
            'toYear' => 'required',
            'toMonth' => 'required'
            
        ]);

            if($request->has('print')){
                if($request->fromYear > $request->toYear){
                    return back()->withErrors(['fromYear' => 'Date From Year should not be greater than Date To Year']);
                }

                if($request->fromMonth > $request->toMonth){
                    return back()->withErrors(['fromMonth' => 'Date From Month should not be greater than Date To Month']);
                }

                $author = Author::find($request->author);
                $pods = collect();
                $totalPods = collect(['title' => 'Grand Total', 'quantity' =>  0, 'price' => 0, 'revenue'=> 0, 'royalty' => 0]);
                foreach($request->book as $book){
                    $podTransactions = PodTransaction::orderBy('quantity','DESC')->orderBy('month','DESC')->where('author_id', $request->author)->where('book_id', $book)
                                            ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                            ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                            ->get();

                    if(count($podTransactions) > 0){
                        $years = [];
                        $months = [];
                        foreach($podTransactions as $key=>$pod){
                            if(!in_array($pod->year, $years)){ array_push($years, $pod->year); }
                            if(!in_array($pod->month, $months)){ array_push($months, $pod->month); }
                        }

                        foreach($years as $year){
                            foreach($months as $month){
                                $podFirst = $podTransactions->where('year', $year)->where('month', $month)->first();

                                if($podFirst){
                                    /* Get all PaperBack PodTransaction */
                                    $perfectbound = $podTransactions->where('year', $year)->where('month', $month)->where('format', 'Perfectbound');
                                    $paperBackquan = 0;
                                    $paperRev = 0;
                                    $paperHigh = 0;
                                    $paperRoyal = 0;
                                    foreach ($perfectbound as $pod){
                                        $paperBackquan += $pod->quantity;
                                        $paperRev += $pod->price * $pod->quantity;
                                        if($pod->price > $paperHigh) { $paperHigh = $pod->price; }
                                        if ($pod->royalty > $paperRoyal) { $paperRoyal = $pod->royalty;}
                                    }

                                    $paperRoyalty = number_format($paperRev * 0.15,2) ;
                                    $paperRev  = number_format($paperRev ,2);
                                    $pods->push(['title' => $podFirst->book->title, 'year' => $year, 'month' => $month, 'format' => 'Paperback', 'quantity' => $paperBackquan, 'price' => '$'.number_format($paperHigh, 2), 'revenue'=>'$'. number_format($paperRev, 3), 'royalty' =>'$'. number_format($paperRoyal,3)]);

                                    /* Get all  Laminated  Transactions */
                                    $hardBound = $podTransactions->where('year', $year)->where('month', $month)->where('format', '!=', 'Perfectbound');
                                    $hardBackQuan = 0;
                                    $hardbackRev = 0;
                                    $hardHigh = 0;
                                    $hardRoyal = 0;
                                    foreach ($hardBound as $pod){
                                        $hardBackQuan += $pod->quantity;
                                        $hardbackRev += $pod->price * $pod->quantity;
                                        if($pod->price > $hardHigh) { $hardHigh = $pod->price; }
                                        if ($pod->royalty > $hardRoyal) { $hardRoyal = $pod->royalty;}
                                    }

                                    $hardRoyalty = number_format($hardbackRev * 0.15 ,2);
                                    $hardbackRev  = number_format($hardbackRev ,2);
                                    $pods->push(['title' => $podFirst->book->title, 'year' => $year, 'month' => $month, 'format' => 'Hardback', 'quantity' =>  $hardBackQuan, 'price' =>'$'. number_format($hardHigh, 2) ,'revenue'=> '$'.number_format($hardbackRev, 2), 'royalty' =>'$'. number_format($hardRoyalty,3)]);
                                    
                                }   
                            }
                        }
                        $allCopies = $podTransactions->sum('quantity');
                       
                        if($allCopies > 1){
                            $royalties = number_format($podTransactions->sum('royalty'),2);
                           
                        }
                        else{
                            $royalties = number_format($paperRoyal, 2) ?? number_format($hardRoyal, 2);
                        }
                        $pods->push([
                            'books' => $podTransactions[0]->book->id ,
                            'title' => $podTransactions[0]->book->title . " Total",
                            'quantity' =>$allCopies,
                            'revenue' => number_format($paperRev + $hardbackRev, 2),
                            
                            'royalty' =>$royalties,
                            'price' => (($paperHigh > $hardHigh) ? number_format($paperHigh, 2) : number_format($hardHigh, 2))
                        ]);
                    }
                
                    
                $grand_quantity = 0;
                $grand_royalty = 0;
                $grand_price = 0;
                $grand_revenue = 0;
                foreach($pods as $pod){
                    if(UtilityHelper::hasTotalString($pod)){
                        $grand_quantity += $pod['quantity'];
                        if($grand_quantity > 1){
                            $grand_royalty += floor($pod['royalty']*100)/ 100  ;
                        }else{
                            $grand_royalty += $pod['royalty'] ;
                        }
                      
                        $grand_revenue += $pod['revenue'];
                    }
                    if($pod['price'] > $grand_price) { $grand_price = $pod['price']; }
                }
                $totalPods['quantity'] = $grand_quantity;
                $totalPods['price'] = number_format($grand_price, 2);
                $totalPods['revenue'] = number_format($grand_revenue, 2);
                 $totalPods['royalty'] = number_format($grand_royalty,2);      
            }

                $ebooks = collect();
                $totalEbooks = collect(['title' => 'Grand Total' , 'quantity' => 0, 'revenue' => 0, 'royalty' => 0]);
        
                foreach($request->book as $book){
                    $ebookTransactions = EbookTransaction::where('author_id', $request->author)->where('book_id', $book)
                                                ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                                ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                                ->where('royalty', '<>', 0)
                                                ->get();
        
                    if(count($ebookTransactions) > 0){
                        $years = [];
                        $months = [];
                        foreach($ebookTransactions as $ebook)
                        {
                            if(!in_array($ebook->year, $years)){
                                array_push($years, $ebook->year);
                            }
                            if(!in_array($ebook->month, $months)){
                                array_push($months, $ebook->month);
                            }
                        }
        
                        foreach($years as $year)
                        {
                            foreach($months as $month){
                                $ebook = $ebookTransactions->where('year', $year)->where('month', $month)->first();
                                if($ebook){
                                    $quantity = $ebookTransactions->where('year', $year)->where('month', $month)->sum('quantity');
                                    $revenue = number_format($ebook->price * $quantity ,2);
                                    $royalty = number_format((float)$ebookTransactions->where('year', $year)->where('month', $month)->sum('royalty'), 2);
                                    
                                    $ebooks->push(['title' => $ebook->book->title, 'year' => $year, 'trade'=>$ebook->class_of_trade, 'month' => $month,'quantity' => $quantity, 'price' => $ebook->price, 'revenue' => $revenue, 'royalty' => $royalty]);
                                }
                            }
                        }
        
                        $ebooks->push([
                            'books' => $ebookTransactions[0]->book->id ,
                            'title' => $ebookTransactions[0]->book->title . " Total",
                            'quantity' => $ebookTransactions->sum('quantity'),
                           
                            'royalty' => number_format((float)$ebookTransactions->sum('royalty'), 2),
                            'price' => $ebookTransactions[0]->price,
                            'revenue' => number_format( $ebookTransactions->sum('quantity') * $ebookTransactions[0]->price ,2)
                        ]);
                    }
                }
        
                foreach($ebooks as $ebook){
                    if(UtilityHelper::hasTotalString($ebook)){
                        if(UtilityHelper::hasTotalString($ebook)){
                            $totalEbooks->put('quantity',$totalEbooks['quantity'] + $ebook['quantity']);
                            $totalEbooks->put('royalty', $totalEbooks['royalty'] + $ebook['royalty']);
                            $totalEbooks->put('revenue', $totalEbooks['revenue'] + $ebook['revenue']);
                            $totalEbooks->put('price',  $ebook['price']);
                        }
                    
                    }
                }
        
                $totalRoyalties = number_format($totalPods['royalty'] + $totalEbooks['royalty'],2);
                $numberFormatter = NumberFormatterHelper::numtowords($totalRoyalties);
                $currentDate = Carbon::now()->format(' m/d/Y g:i A');
        
                $imageUrl = asset('images/header.png');
          //print pdf
                $pdf = PDF::loadView('report.pdf',[
                    'pods' => $pods,
                    'ebooks' => $ebooks,
                    'author' => $author,
                    'totalPods' => $totalPods,
                    'totalEbooks' => $totalEbooks,
                    'totalRoyalties' => $totalRoyalties,
                    'fromYear' => $request->fromYear,
                    'fromMonth' => $request->fromMonth,
                    'toYear' => $request->toYear,
                    'toMonth' => $request->toMonth,
                    'numberFormatter' => $numberFormatter,
                    'currentDate' => $currentDate,
                    'imageUrl' => $imageUrl,
                ]);
              $authorName = $author->getFullName();
              $date = $request->fromMonth.$request->fromYear.htmlentities('-').$request->toMonth.$request->toYear ;
                return $pdf->download($authorName.$date.'Royalty.pdf');
        
            }
            elseif($request->has('preview')){
                if($request->fromYear > $request->toYear){
                    return back()->withErrors(['fromYear' => 'Date From Year should not be greater than Date To Year']);
                }

                if($request->fromMonth > $request->toMonth){
                    return back()->withErrors(['fromMonth' => 'Date From Month should not be greater than Date To Month']);
                }

                $author = Author::find($request->author);
                $pods = collect();
                $totalPods = collect(['title' => 'Grand Total', 'quantity' =>  0, 'price' => 0, 'revenue'=> 0, 'royalty' => 0]);
                foreach($request->book as $book){
                    $podTransactions = PodTransaction::orderBy('quantity','DESC')->orderBy('month','DESC')->where('author_id', $request->author)->where('book_id', $book)
                                            ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                            ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                            ->get();

                    if(count($podTransactions) > 0){
                        $years = [];
                        $months = [];
                        foreach($podTransactions as $key=>$pod){
                            if(!in_array($pod->year, $years)){ array_push($years, $pod->year); }
                            if(!in_array($pod->month, $months)){ array_push($months, $pod->month); }
                        }

                        foreach($years as $year){
                            foreach($months as $month){
                                $podFirst = $podTransactions->where('year', $year)->where('month', $month)->first();

                                if($podFirst){
                                    /* Get all PaperBack PodTransaction */
                                    $perfectbound = $podTransactions->where('year', $year)->where('month', $month)->where('format', 'Perfectbound');
                                    $paperBackquan = 0;
                                    $paperRev = 0;
                                    $paperHigh = 0;
                                    $paperRoyal = 0;
                                    foreach ($perfectbound as $pod){
                                        $paperBackquan += $pod->quantity;
                                        $paperRev += $pod->price * $pod->quantity;
                                        if($pod->price > $paperHigh) { $paperHigh = $pod->price; }
                                        if ($pod->royalty > $paperRoyal) { $paperRoyal = $pod->royalty;}
                                    }

                                    $paperRoyalty = number_format($paperRev * 0.15,2) ;
                                    $paperRev  = number_format($paperRev ,2);
                                    $pods->push(['title' => $podFirst->book->title, 'year' => $year, 'month' => $month, 'format' => 'Paperback', 'quantity' => $paperBackquan, 'price' => '$'.number_format($paperHigh, 2), 'revenue'=>'$'. number_format($paperRev, 3), 'royalty' =>'$'. number_format($paperRoyal,3)]);

                                    /* Get all  Laminated  Transactions */
                                    $hardBound = $podTransactions->where('year', $year)->where('month', $month)->where('format', '!=', 'Perfectbound');
                                    $hardBackQuan = 0;
                                    $hardbackRev = 0;
                                    $hardHigh = 0;
                                    $hardRoyal = 0;
                                    foreach ($hardBound as $pod){
                                        $hardBackQuan += $pod->quantity;
                                        $hardbackRev += $pod->price * $pod->quantity;
                                        if($pod->price > $hardHigh) { $hardHigh = $pod->price; }
                                        if ($pod->royalty > $hardRoyal) { $hardRoyal = $pod->royalty;}
                                    }

                                    $hardRoyalty = number_format($hardbackRev * 0.15 ,2);
                                    $hardbackRev  = number_format($hardbackRev ,2);
                                    $pods->push(['title' => $podFirst->book->title, 'year' => $year, 'month' => $month, 'format' => 'Hardback', 'quantity' =>  $hardBackQuan, 'price' =>'$'. number_format($hardHigh, 2) ,'revenue'=> '$'.number_format($hardbackRev, 2), 'royalty' =>'$'. number_format($hardRoyalty,3)]);
                                    
                                }   
                            }
                        }
                        $allCopies = $podTransactions->sum('quantity');
                       
                        if($allCopies > 1){
                            $royalties = number_format($podTransactions->sum('royalty'),2);
                           
                        }
                        else{
                            $royalties = number_format($paperRoyal, 2) ?? number_format($hardRoyal, 2);
                        }
                        $pods->push([
                            'books' => $podTransactions[0]->book->id ,
                            'title' => $podTransactions[0]->book->title . " Total",
                            'quantity' =>$allCopies,
                            'revenue' => number_format($paperRev + $hardbackRev, 2),
                            
                            'royalty' =>$royalties,
                            'price' => (($paperHigh > $hardHigh) ? number_format($paperHigh, 2) : number_format($hardHigh, 2))
                        ]);
                    }
                
                    
                $grand_quantity = 0;
                $grand_royalty = 0;
                $grand_price = 0;
                $grand_revenue = 0;
                foreach($pods as $pod){
                    if(UtilityHelper::hasTotalString($pod)){
                        $grand_quantity += $pod['quantity'];
                        if($grand_quantity > 1){
                            $grand_royalty += floor($pod['royalty']*100)/ 100  ;
                        }else{
                            $grand_royalty += $pod['royalty'] ;
                        }
                      
                        $grand_revenue += $pod['revenue'];
                    }
                    if($pod['price'] > $grand_price) { $grand_price = $pod['price']; }
                }
                $totalPods['quantity'] = $grand_quantity;
                $totalPods['price'] = number_format($grand_price, 2);
                $totalPods['revenue'] = number_format($grand_revenue, 2);
                 $totalPods['royalty'] = number_format($grand_royalty,2);      
            }
                
             

                $ebooks = collect();
                $totalEbooks = collect(['title' => 'Grand Total' , 'price' => 0  ,'quantity' => 0, 'revenue' => 0, 'royalty' => 0]);
        
                foreach($request->book as $book){
                    $ebookTransactions = EbookTransaction::where('author_id', $request->author)->where('book_id', $book)
                                                ->where('year', '>=', $request->fromYear)->where('year','<=', $request->toYear)
                                                ->where('month', '>=', (int) $request->fromMonth )->where('month', '<=', (int) $request->toMonth)
                                                ->where('royalty', '<>', 0)
                                                ->get();
        
                    if(count($ebookTransactions) > 0){
                        $years = [];
                        $months = [];
                        foreach($ebookTransactions as $ebook)
                        {
                            if(!in_array($ebook->year, $years)){
                                array_push($years, $ebook->year);
                            }
                            if(!in_array($ebook->month, $months)){
                                array_push($months, $ebook->month);
                            }
                        }
        
                        foreach($years as $year)
                        {
                            foreach($months as $month){
                                $ebook = $ebookTransactions->where('year', $year)->where('month', $month)->first();
                                if($ebook){
                                    $quantity = $ebookTransactions->where('year', $year)->where('month', $month)->sum('quantity');
                                    $revenue = number_format($ebook->price * $quantity ,2);
                                    $royalty = number_format((float)$ebookTransactions->where('year', $year)->where('month', $month)->sum('royalty'), 2);
                                    
                                    $ebooks->push(['title' => $ebook->book->title, 'year' => $year, 'trade'=>$ebook->class_of_trade, 'month' => $month,'quantity' => $quantity, 'price' => $ebook->price, 'revenue' => $revenue, 'royalty' => $royalty]);
                                }
                            }
                        }
        
                        $ebooks->push([
                            'books' => $ebookTransactions[0]->book->id ,
                            'title' => $ebookTransactions[0]->book->title . " Total",
                            'quantity' => $ebookTransactions->sum('quantity'),
                           
                            'royalty' => number_format((float)$ebookTransactions->sum('royalty'), 2),
                            'price' => $ebookTransactions[0]->price,
                            'revenue' => number_format( $ebookTransactions->sum('quantity') * $ebookTransactions[0]->price ,2)
                        ]);
                    }
                }
        
                foreach($ebooks as $ebook){
                    if(UtilityHelper::hasTotalString($ebook)){
                        $totalEbooks->put('quantity',$totalEbooks['quantity'] + $ebook['quantity']);
                        $totalEbooks->put('royalty', $totalEbooks['royalty'] + $ebook['royalty']);
                        $totalEbooks->put('revenue', $totalEbooks['revenue'] + $ebook['revenue']);
                        $totalEbooks->put('price',  $ebook['price']);
                    }
                }
        
                $totalRoyalties = number_format((float) $totalPods['royalty'] + $totalEbooks['royalty'], 3);
                $numberFormatter = NumberFormatterHelper::numtowords($totalRoyalties);
                $currentDate = Carbon::now();
                // preview data 
                return view('prev',[
                    
                    
                    'pods' => $pods,
                    'ebooks' => $ebooks,
                    'author' => $author,
                    'totalPods' => $totalPods,
                    'totalEbooks' => $totalEbooks,
                    'totalRoyalties' => $totalRoyalties,
                    'fromYear' => $request->fromYear,
                    'fromMonth' => $request->fromMonth,
                    'toYear' => $request->toYear,
                    'toMonth' => $request->toMonth,
                    'numberFormatter' => $numberFormatter,
                    'currentDate' => $currentDate,
                   
                ]);
        
            }
            

           
        
       

    }
}