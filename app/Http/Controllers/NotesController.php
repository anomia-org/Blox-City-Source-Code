<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function index($page)
    {
        switch ($page) {
            case 'terms':
                $title = 'Terms of Service';
                $active = 'terms';
                $file = 'notes._terms';
                break;
            case 'privacy':
                $title = 'Privacy Policy';
                $active = 'privacy';
                $file = 'notes._privacy';
                break;
            case 'about':
                $title = 'About';
                $active = 'about';
                $file = 'notes._about';
                break;
            case 'jobs':
                $title = 'Jobs';
                $active = 'jobs';
                $file = 'notes._jobs';
                break;
            case 'team':
                $title = 'Team';
                $active = 'team';
                $file = 'notes._team';
                break;
			case 'locked':
                $title = 'locked';
                $active = 'locked';
                $file = 'notes._locked';
                break;
            case 'contact':
                $title = 'Contact';
                $active = 'contact';
                $file = 'notes._contact';
                break;
            default:
                abort(404);
        }

        return view('notes.index', [
            'title' => $title,
            'active' => $active,
            'file' => $file
        ]);
    }
}
