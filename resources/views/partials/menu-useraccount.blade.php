    <li class="dropdown user user-menu">
        <!-- Menu Toggle Button -->
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!-- The user image in the navbar-->
            <img src="/img/logosoprema.jpg" class="user-image" alt="User Image"/>
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span class="hidden-xs">{{ $user->first_name }}</span>
        </a>
        <ul class="dropdown-menu">
            <!-- The user image in the menu -->
            <li class="user-header">
                <img src="/img/logosoprema.jpg" class="img-circle" alt="User Image" />
                <p>
                    {{ $user->first_name }} {{ $user->last_name }}
                </p>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
                <div class="pull-left">
                    <a href="{{ route('user.edit-my-profile') }}" class="btn btn-default btn-flat">{{ trans('partial.profile') }}</a>
                </div>
                <div class="pull-right">
                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">{{ trans('partial.signout') }}</a>
                </div>
            </li>
        </ul>
    </li>
