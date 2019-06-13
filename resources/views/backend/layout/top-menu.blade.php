 <div id="top-menu">
    <div class="row">
        <div class="large-2 medium-4 small-12 columns top-part-no-padding">
            <div class="logo-bg">
                {{ HTML::image(config('asset.image_path.icon') . 'map-icon.png', trans('messages.logo'), ['class' => 'logo-admin']) }}
                 <a href="{{ route('adminPage') }}" class="logo-title"> myplaces</a>
                <i class="fi-list toggles" data-toggle="hide"></i>
            </div>
        </div>
        <div class="large-10 medium-8 small-12 columns top-menu">
            <div class="row">
                <div class="large-6 medium-6 small-12 columns">
                    <div class="row">
                        <div class="large-8 columns">
                            <input id="search" type="text" class="search-text" placeholder="{{ trans('messages.search') }}" />
                        </div>
                    </div>
                </div>
                <div class="large-4 medium-6 small-12 columns text-center">
                    <div class="row">
{{--                         <div class="medium-3 small-3 columns">
                            <div class="notification">
                                <i class="fi-mail"></i>
                                <span class="mail">4</span>
                            </div>
                        </div>
                        <div class="medium-3 small-3 columns">
                            <div class="notification">
                                <i class="fi-megaphone"></i>
                                <span class="megaphone">5</span>
                            </div>
                        </div>
                        <div class="medium-3 small-3 columns">
                            {{ HTML::image(config('asset.image_path.icon') . 'map-icon.png', trans('messages.logo'), ['class' => 'logo-admin']) }}
                        </div>
                        <div class="medium-3 small-3 columns">
                            <i class="fi-power power-off"></i>
                        </div>
 --}}                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
