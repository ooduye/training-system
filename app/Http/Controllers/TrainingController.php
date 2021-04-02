<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TrainingController extends Controller
{
    protected $baseUrl = 'http://training-system.test/api/v1/';

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = array(
                'username' => 'required',
                'password' => 'required',
            );

            $response = Http::post($this->baseUrl .'auth/login', [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if ($response->status() == '200') {
                Session::put('user_token', $response['token']);
                Session::put('profile', $response['profile']);

                return redirect()->intended('/');
            } else if ($response->status() == '401') {
                $errors = $response['message'];
                return view('errors.401', array(
                    'error' => $errors,
                ));
            }
        }

        return view('auth.login');
    }

    public function logout(Request $request)
    {
        $response = Http::withToken(Session::get('user_token'))->get($this->baseUrl .'auth/logout');

        if ($response->status() == '200') {
            Session::forget('user_token');
            Session::forget('profile');

            return redirect()->intended('/');
        } else if ($response->status() == '401') {
            $errors = $response['message'];
            return view('errors.401', array(
                'error' => $errors,
            ));
        }
    }

    public function getAllSkills(Request $request) {
        $response = Http::withToken(Session::get('user_token'))->get($this->baseUrl .'skills');

        if ($response->status() == '200') {
            return view('pages.skills', array(
                'skills' => $response['data'],
            ));
        } else if ($response->status() == '401') {
            $errors = $response['message'];
            return view('errors.401', array(
                'error' => $errors,
            ));
        }
    }

    public function getSkillActivities($id) {
        $response = Http::withToken(Session::get('user_token'))->get($this->baseUrl .'activities/' . $id);

        if ($response->status() == '200') {
            return view('pages.skill', array(
                'activities' => $response['data']['activities'],
            ));
        } else if ($response->status() == '401') {
            $errors = $response['message'];
            return view('errors.401', array(
                'error' => $errors,
            ));
        }
    }
}
