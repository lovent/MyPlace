@extends('backend.master')
@section('main')
    <div class="large-10 medium-12 small-12 columns light-grey-bg-pattern">
        <div class="row">
            <div class="large-10 columns">
                <div class="page-name">
                    <h3 class="left">{{ trans('messages.dashboard') }}</h3>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div id="dashboard">
            <div class="row">
                <div class="large-3 medium-6 small-12 columns">
                    <div class="stats border">
                        <div class="left">
                            {{ trans('messages.film') }}
                            <h4>{{ $numberReport }} {{ trans('messages.film') }}</h4>
                        </div>
                        <i class="fi-price-tag right sales"></i>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="large-3 medium-6 small-12 columns">
                    <div class="stats border">
                        <div class="left">
                            {{ trans('messages.users') }}
                            <h4>{{ $numberUser }} {{ trans('messages.users') }}</h4>
                        </div>
                        <i class="fi-torso right user"></i>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="large-3 medium-6 small-12 columns">
                    <div class="stats border">
                        <div class="left">
                            {{ trans('messages.room') }}
                            <h4>3 {{ trans('messages.room') }}</h4>
                        </div>
                        <i class="fi-shopping-cart right orders"></i>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="large-3 medium-6 small-12 columns">
                    <div class="stats border">
                        <div class="left">
                            {{ trans('messages.profit') }}
                            <h4>1.000.000.000 VND</h4>
                        </div>
                        <i class="fi-dollar right profit"></i>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="large-6 columns">
                    <div class="custom-panel">
                        <div class="custom-panel-heading">
                            <h4>{{ trans('messages.quick-message') }}</h4>
                        </div>
                        <div class="custom-panel-body">
                            <label>{{ trans('messages.address') }}</label>
                            <input id="Text2" type="text" />
                            <label>{{ trans('messages.subject') }}</label>
                            <input id="Text3" type="text" />
                            <label>{{ trans('messages.messages') }}</label>
                            <textarea></textarea>
                            <a href="#" class="button tiny radius coral-bg right">{{ trans('messages.send') }}</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="large-6 columns">
                    <div class="custom-panel">
                        <div class="custom-panel-heading">
                            <h4>{{ trans('messages.to-do') }}</h4>
                        </div>
                        <div class="custom-panel-body">
                            <input id="new-todo" type="text" placeholder="{{ trans('messages.need-done') }}">
                            <ul id="todo-list">
                               
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
