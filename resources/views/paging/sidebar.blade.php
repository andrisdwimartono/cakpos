              <!-- Sidebar  -->
              <nav id="sidebar">
                <div class="sidebar-header">
                    <h3>CAKRUD</h3>
                    <strong>CR</strong>
                </div>

                <ul class="list-unstyled components">
                @foreach ($usermenus as $um)
                @if ($last_parent_id != $um->parent_id && $last_parent_id != 0)
                    </ul>
                  </li>
                @endif
                @if ($um->is_group_menu == 'on')
                  <li{{$parent_active_id == $um->menu_id ? ' class=active' : '' }}>
                    <a href="#submenu{{$um->mp_sequence}}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle caksidemenu">
                        <i class="fas {{$um->menu_icon}}"></i>
                        <span> {{$um->menu_name}}</span>
                    </a>
                    <ul class="collapse list-unstyled{{$parent_active_id == $um->menu_id ? ' show' : '' }}" id="submenu{{$um->mp_sequence}}">
                @elseif (!isset($um->is_group_menu) && !isset($um->parent_id))
                  @if ($um->is_shown_at_side_menu == 'on')
                      <li{{ Request::is($um->url) ? ' class=active' : '' }}>
                        <a href="{{url($um->url)}}" class="caksidemenu">
                          <i class="nav-icon fas {{$um->menu_icon}}"></i>
                          <span> {{$um->menu_name}}</span>
                        </a>
                      </li>
                  @endif
                @else
                  @if ($um->is_shown_at_side_menu == 'on')
                      <li{{ Request::is($um->url) ? ' class=active' : '' }}>
                          <a href="{{url($um->url)}}" class="caksidemenu caksidechildmenu">
                            <i class="fas {{$um->menu_icon}}"></i>
                            <span> {{$um->menu_name}}</span>
                          </a>
                      </li>
                  @endif
                @endif
                @php @$last_parent_id=$um->parent_id; @endphp
                @endforeach
              </ul>
            </nav>