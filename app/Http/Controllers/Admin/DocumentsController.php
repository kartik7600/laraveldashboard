<?php

namespace App\Http\Controllers\Admin;

use App\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Validator, Redirect, Hash, DB, Session, Auth, File;
use App\History;
use App\ClientDetail;
use App\TRN;

class DocumentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.role')
                ->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $TrnID = $request->id;

        /*if( Auth::user()->user_role == 'client' && Auth::user()->uid != $TrnID )
        {
            return redirect::route('documents.index', ['id'=>Auth::user()->uid]);
        }*/

        $documents = Document::where([
                                        ['trn_id', $TrnID]
                                    ])
                                    ->orderBy('created_at', 'ASC')
                                    ->get();

        return view('admin.documents.index', compact('TrnID', 'documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $TrnID = $request->id;
        
        return view('admin.documents.create', compact('TrnID'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = Input::only(
            'document_name',
            'document_file'
        );

        $rules = [
            'document_name' => 'required',
            'document_file' => 'required|mimes:pdf',
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        // Insert record
        $document = new Document;

        $document->admin_id = Auth::user()->uid;
        $document->trn_id = (Input::get('trn_id'));
        $document->document_name = (Input::get('document_name'));

        if( Input::hasFile('document_file') )
        {
            if( Input::file('document_file')->isValid() )
            {
                $destinationPath = 'public/uploads/documents'; // upload path

                $OriginalFilename = Input::file('document_file')->getClientOriginalName(); // getting image extension

                $fileParts = pathinfo($OriginalFilename);
                $fileName = 'document_'.$fileParts['filename'].'_'.date('Ymdhms').'.'.$fileParts['extension'];

                Input::file('document_file')->move($destinationPath, $fileName);
                $document->document_file = $fileName;
            }
        }


        $document->save();

        if( $document->did > 0 )
        {
            // store history start
            $trnDtail = TRN::findOrFail($document->trn_id);

            $clientDtail = ClientDetail::findOrFail($trnDtail->client_detail_id);
            
            $activity = 'Tax Register Number '.$trnDtail->trn_tax_register_number.' new document added';

            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $clientDtail->user_id,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'Document added successfully.');

            return redirect::route('documents.index', ['id'=>$document->trn_id]);
        }

        Session::flash('messageType', 'error');
        Session::flash('message', 'Can\'t document add.');

        return redirect::back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        $document = Document::findOrFail($document->did);

        return view('admin.documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        $inputs = Input::only(
            'document_name',
            'document_file'
        );

        $rules = [
            'document_name' => 'required',
            'document_file' => 'nullable|mimes:pdf',
        ];

        $validator = Validator::make( $inputs, $rules );

        if( $validator->fails() )
        {
            return redirect::back()->withInput()->withErrors( $validator );
        }

        // Insert record
        $document = Document::findOrFail($document->did);

        $document->document_name = (Input::get('document_name'));

        if( Input::hasFile('document_file') )
        {
            if( Input::file('document_file')->isValid() )
            {
                $destinationPath = 'public/uploads/documents'; // upload path

                $oldFile = public_path('uploads/documents/'.$document->document_file);
                if( !empty($document->document_file) && File::exists($oldFile) )
                {
                    unlink($oldFile);
                }

                $OriginalFilename = Input::file('document_file')->getClientOriginalName(); // getting image extension

                $fileParts = pathinfo($OriginalFilename);
                $fileName = 'document_'.$fileParts['filename'].'_'.date('Ymdhms').'.'.$fileParts['extension'];

                Input::file('document_file')->move($destinationPath, $fileName);
                $document->document_file = $fileName;
            }
        }


        $document->save();

        if( $document->did > 0 )
        {
            // store history start
            $trnDtail = TRN::findOrFail($document->trn_id);

            $clientDtail = ClientDetail::findOrFail($trnDtail->client_detail_id);
            
            $activity = 'Tax Register Number '.$trnDtail->trn_tax_register_number.' document updated';

            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $clientDtail->user_id,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'Document updated successfully.');

            return redirect::route('documents.index', ['id'=>$document->trn_id]);
        }

        Session::flash('messageType', 'error');
        Session::flash('message', 'Can\'t document updated.');

        return redirect::back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $document = Document::findOrFail($document->did);
        $trn_id = $document->trn_id;

        // delete report file
        $file = public_path('uploads/documents/'.$document->document_file);

        if( !empty($document->document_file) && File::exists($file) )
        {
            unlink($file);
        }

        if( $document->delete() )
        {
            // store history start
            $trnDtail = TRN::findOrFail($trn_id);

            $clientDtail = ClientDetail::findOrFail($trnDtail->client_detail_id);
            
            $activity = 'Tax Register Number '.$trnDtail->trn_tax_register_number.' document deleted';

            $history = History::create([
                    'added_by_id' => Auth::user()->uid,
                    'user_id' => $clientDtail->user_id,
                    'activity' => $activity
                ]);
            // store history end

            Session::flash('messageType', 'success');
            Session::flash('message', 'Document deleted successfully.');
        }
        else
        {
            Session::flash('messageType', 'error');
            Session::flash('message', 'Can\'t delete document.');
        }

        return redirect::back();
    }
}
