<?php

use Illuminate\Support\Facades\Auth;
use Jobful\HistoryTracking\Exceptions\CouldNotLogActivity;
use Jobful\HistoryTracking\Facades\CauserResolver;
use Jobful\HistoryTracking\Test\Models\Article;
use Jobful\HistoryTracking\Test\Models\User;

it('can resolve current logged in user', function () {
    Auth::login($user = User::first());

    $causer = CauserResolver::resolve();

    expect($causer)->toBeInstanceOf(User::class);
    expect($causer->id)->toEqual($user->id);
});

it('will throw an exception if it cannot resolve user by id', function () {
    $this->expectException(CouldNotLogActivity::class);

    CauserResolver::resolve(9999);
});

it('can resloved user from passed id', function () {
    $causer = CauserResolver::resolve(1);

    expect($causer)->toBeInstanceOf(User::class);
    expect($causer->id)->toEqual(1);
});

it('will resolve the provided override callback', function () {
    CauserResolver::resolveUsing(fn () => Article::first());

    $causer = CauserResolver::resolve();

    expect($causer)->toBeInstanceOf(Article::class);
    expect($causer->id)->toEqual(1);
});

it('will resolve any model', function () {
    $causer = CauserResolver::resolve($article = Article::first());

    expect($causer)->toBeInstanceOf(Article::class);
    expect($causer->id)->toEqual($article->id);
});
