<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();
        return view('book.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('book.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'year' => 'required|integer',
            'price' => 'required|integer'
        ]);
        $book = new Book([
            'title' => $request->get('title'),
            'author' => $request->get('author'),
            'year' => $request->get('year'),
            'price' => $request->get('price')
        ]);
        $book->save();
        return redirect('book')->with('success', 'Successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
        return view('book.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'year' => 'required|integer',
            'price' => 'required|integer'
        ]);
        $book = Book::find($id);        
        $book->title = $request->get('title');
        $book->author = $request->get('author');
        $book->year = $request->get('year');
        $book->price = $request->get('price');
        
        $status = 'success';
        $message= 'Updated';
        if(!$book->save()){
            $status = 'error';
            $message= 'Failed to update';
        }

        return redirect('book')->with($status, $message);
    }

    public function del($id)
    {
        $status = 'success';
        $message= 'Data deleted';
        $book = Book::find($id);
        
        if(!$book->delete()){
            $status = 'error';
            $message= 'Failed to delete';
        }

        return redirect('book/index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = 'success';
        $message= 'Data deleted';
        $book = Book::find($id);
        
        if(!$book->delete()){
            $status = 'error';
            $message= 'Failed to delete';
        }

        return redirect('book/index')->with($status, $message);
    }
}
