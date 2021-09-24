                  <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">

                        <button type="button" id="sidebarCollapse" class="btn btn-info">
                            <i class="fas fa-bars"></i>
                        </button>
                        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-align-justify"></i>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="/home">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/selling">Penjualan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/purchasing">Pembelian</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/customer">Pelanggan</a>
                                </li>
                            </ul>
                            <ul class="navbar-nav p-2">
                                <!-- <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-bell cakicon-navbar" style="font-size: 28px"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                        <a class="dropdown-item" href="/user/{{Auth::user()->id}}/edit"><span>Edit Profile</span></a>
                                        <a class="dropdown-item" href="#"><span>Log Out</span></a>
                                    </div>
                                </li> -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="{{ asset ("/photo_profile") }}/{{Auth::user()->photo_profile}}" width="40" height="40" class="rounded-circle cakicon-navbar">
                                        
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                        <label class="dropdown-item" style="border-bottom: 0.5px solid #dadada;"><strong>Hi, {{Auth::user()->name}} !</strong></label>
                                        <a class="dropdown-item" href="/user/editprofile"><span>Edit Profile</span></a>
                                        <a class="dropdown-item" href="/logout"><span>Log Out</span></a>
                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>                
                </nav>