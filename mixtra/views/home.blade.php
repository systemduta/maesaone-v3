@extends('mixtra::layouts.app')

@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
			
        <!-- Page Content -->
        <div class="content container-fluid">

            @if (Session::has('message'))
            <?php
                $message_type = Session::pull('message_type');
                $message = Session::pull('message');
                // dd($message);
            ?>
            <div class="alert alert-{{ $message_type }} alert-dismissible fade show">
                <div class="alert-body">
                    <h3 class="text-{{ $message_type }}">
                        <i class="fa fa-{{ $message_type }}"></i> {{ $message_type }}
                    </h3> 
                    {{ $message }}
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            @endif
                        
            <!-- Kick start -->
            <div class="card">
                <div class="card-header">
                    <h3 class="page-title">{{ MITBooster::getSetting('app_name') }}</h4>
                    <h4 class="card-title">Kick start your next project 🚀</h4>
                </div>
            
                
                <div class="card-body">
                    <div class="card-text">
                        <p>
                        Getting start with your project custom requirements using a ready template which is quite difficult and time
                        taking process, Vuexy Admin provides useful features to kick start your project development with no efforts !
                        </p>
                        <ul>
                        <li>
                            Vuexy Admin provides you getting start pages with different layouts, use the layout as per your custom
                            requirements and just change the branding, menu &amp; content.
                        </li>
                        <li>
                            Every components in Vuexy Admin are decoupled, it means use use only components you actually need! Remove
                            unnecessary and extra code easily just by excluding the path to specific SCSS, JS file.
                        </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Kick start -->

        </div>
        <!-- /Page Content -->
        
    </div>
    <!-- /Page Wrapper -->

@endsection