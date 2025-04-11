<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Models\Tickets;

class TicketController extends Controller
{
    protected $tickets;
    protected $statuses;
    public function __construct(Tickets $tickets){
        $this->tickets = $tickets;
        $this->statuses = [
            1 => 'To-Do',
            2 => 'In-Progress',
            3 => 'Done'
        ];
    }

    public function index(Request $request): View
    {
        $status = $this->statuses;
        return view('tickets.index', compact('status')) ;
    }

    public function create(): View
    {
        return view('tickets.create');
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $tickets = $this->tickets::where('created_by',Auth::user()->id)->find($id );
        if(!$tickets) {
            return redirect()->route('tickets.index')->with('error', 'Ticket does not exist');
        }
        $status = $this->statuses;
        return view('tickets.edit', compact('tickets', 'status'));
    }

    public function info(Request $request)
    {
        $id = $request->id;
        $tickets = $this->tickets::where('created_by',Auth::user()->id)->find($id );
        if(!$tickets) {
            return redirect()->route('tickets.index')->with('error', 'Ticket does not exist');
        }
        $status = $this->statuses;
        return view('tickets.info', compact('tickets', 'status'));
    }

    public function store(Request $request): RedirectResponse
    {   

            $request->validate([
                'title' => 'required|unique:tickets|string|max:100',
                'content' => 'required|string',
                'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            ]);
        
            
            if (request()->hasFile('attachment'))
            {
                $imageName = time().'.'.$request->attachment->extension();
                $request->attachment->move(public_path('images'), $imageName);
                $this->tickets->attachment = 'images/'.$imageName;
            }

            $this->tickets->title =  $request->title;
            $this->tickets->content =  $request->content;
            $this->tickets->published =  ($request->published) ? 1:0;
            $this->tickets->created_by =  Auth::user()->id;
            $this->tickets->save();
            return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
      
    }

    public function update (Request $request): RedirectResponse
    {
       
        $request->validate([
            'content' => 'required',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'status' => ['required',Rule::in(array_keys($this->statuses))]
        ]);
        
        $id = $request->id;
        $tickets = $this->tickets::where('created_by',Auth::user()->id)->find($id );
        if (request()->hasFile('attachment'))
        {
            $imageName = time().'.'.$request->attachment->extension();
            $request->attachment->move(public_path('images'), $imageName);
            $tickets->attachment = 'images/'.$imageName;
        }

        $tickets->content =  $request->content;
        $tickets->published =  ($request->published) ? 1:0;
        $tickets->sts =  $request->status;
        $tickets->save();

        return redirect()->route('tickets.info.{id}', $id)->with('success', 'Ticket updated successfully.');
    }

    public function getTickets(Request $request): JsonResponse
    {
        $query = $this->tickets::where('created_by',Auth::user()->id);
        if($request->sSearch!='')
        {
            $keyword = $request->sSearch;
            $query = $query->when($keyword!='', 
            function($q) use($keyword){
                return $q->where('title','like','%'.$keyword.'%');
            });
        }
        if(is_numeric($request->sSearch_2))
        {
            $status = $request->sSearch_2;
            $query = $query->when($status!='', 
            function($q) use($status){
                return $q->where('sts',$status);
            });
        }

        $totalCount = $query->count();

        $columnIndex = $request['iSortCol_0'];
        $order = isset($request['sSortDir_0']) ?$request['sSortDir_0'] :'asc' ;
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'status',
            3 => 'created_at'
            );
        
        $columnName =  isset($columns[$columnIndex]) ? $columns[$columnIndex] :'id';

        $limit = $request->iDisplayLength;
        $offset = $request->iDisplayStart;

        $query = $query->when(($limit != '-1' && isset($offset)),
        function ($q) use ($limit, $offset) {
            return $q->offset($offset)->limit($limit);
        });
        if($columnName =='title') {
            if($order =='desc')
                $query = $query->orderByRaw('LENGTH(title) desc' );
            else 
                $query = $query->orderByRaw('LENGTH(title) asc' );
        }

        $query = $query->orderBy($columnName, $order);

        $results = $query->get();
        $column = array();
        $data = array();
        foreach ($results as $list) {
            $col['id'] = isset($list->id) ? $list->id : "";
            $col['title'] = isset($list->title) ? $list->title : "";
            $col['created_at'] = isset($list->created_at) ? date_format($list->created_at,'d M Y, h:i A') : "";
            $col['published'] = ($list->published) ? "Published" : "Draft";
            if($list->sts==2)
                $col['sts']= 'In-Progress';
            else if($list->sts==3)
                $col['sts']= 'Done';
            else 
                $col['sts']= 'To-Do';

            array_push($column, $col);
        }
        $data['sEcho'] = $request->sEcho;
        $data['aaData'] = $column;
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $data['recordsFiltered']= $totalCount;
        return  response()->json($data);
    }
}
