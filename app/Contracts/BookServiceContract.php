<?php

namespace App\Contracts;

use use Illuminate\Http\Request;

interface BookServiceContract
{
    public function addBook(Request $request);
    public function getBook(Request $request);
    public function removeBook(Request $request);
    public function listBooks(Request $request);
    public function addToLibrary(Request $request);
    public function removeFromLibrary(Request $request);
}
