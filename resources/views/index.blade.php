@php
    // Find scripts and styles from angular dist folder.
    $buildDir = 'dist';
    if(!Storage::disk('public_assets')->exists($buildDir))
        $buildDir = null;

    $allFiles = Storage::disk('public_assets')->files($buildDir); 

    $scripts = [];
    $styles = [];

    if($allFiles) {
        $styles = array_values(preg_grep('~styles.*\.css$~', $allFiles));

        $scripts = array();
        //## Note: 'styles', 'vendor' scripts are only used in development version.
        $order = ['runtime','polyfills','styles','scripts','vendor','main'];
        foreach($order as $value) {
            $scripts = array_merge($scripts, preg_grep('~'.$value.'.*\.js$~', $allFiles));
        }
    }

@endphp
@extends('layouts.main')

@section('content')
    <app-root></app-root>
@endsection

@section('header_styles')
    @if(count($styles))
        <link rel="stylesheet" href="{{ $styles[0] }}">    
    @endif
@endsection

@section('footer_scripts')
    @if(isset($user))
    <script>
       var userInfo ={!! json_encode($user) !!};
    </script>
    @endif

    @if(count($scripts))
        @foreach($scripts as $script)
            @if(strpos($script, 'scripts') !== false)
                <script src="{{ $script }}" ></script>
            @else
                @if(strpos($script, '-es5') !== false)
                    <script src="{{ $script }}" nomodule defer></script>
                @else
                    <script src="{{ $script }}" type="module"></script>
                @endif    
            @endif    
        @endforeach
    @endif

    {{-- <script src="runtime.js" defer></script>
    <script src="polyfills.js" defer></script>
    <script src="vendor.js" defer></script>
    <script src="main.js" defer></script> --}}
@endsection

