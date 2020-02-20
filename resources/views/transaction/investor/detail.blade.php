@extends('base.main')
@section('title') Investor @endsection
@section('page_icon') <i class="fa fa-users"></i> @endsection
@section('page_title') Investor {{ $model->title }} @endsection
@section('menu')
    <div class="box box-solid" style="text-align:right;">
        <div class="box-body">
            <div class="btn-group">
				<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
					<span class="fa fa-bars"></span> Tools <span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu" role="menu" style="-moz-box-shadow: 0px 2px 6px 0px #ccc; box-shadow: 0px 2px 6px 0px #ccc;">
					<li><a href="{{ route('investor.topupform', base64_encode($model->id)) }}">Top-up</a></li>
                    <li><a href="{{ route('investor.cashoutform', base64_encode($model->id)) }}">Cash out</a></li>
				</ul>
			</div>
            <a href="{{ route('investor.create') }}" class="btn btn-success" title="Create">
                <i class="fa fa-plus"></i> Create
            </a>
            <a href="{{ route('investor.index') }}" class="btn btn-success" title="Manage">
                <i class="fa fa-list"></i> Manage
            </a>
        </div>
    </div>
@endsection

@section('content')
@if ($model->is_dummy == 1)
<div class="alert bg-orange" style="text-align:center;">
	<h3 style="margin:0;">DUMMY</h3>
</div>
@endif

<div class="nav-tabs-custom">
	<ul class="nav nav-tabs tabs-up" id="friends">
		<li>
			<a href="#tab_profile" data-toggle="tab" class="active" id="profile_tab" rel="tooltip"> <i class="fa fa-user"></i>&nbsp;&nbsp; Profile </a>
		</li>
		<li>
			<a href="#tab_internet_banking" data-toggle="tab" id="ib_tab" rel="tooltip"> <i class="fa fa-credit-card"></i>&nbsp;&nbsp; Internet Banking </a>
		</li>
		<li>
			<a href="#tab_atm" data-toggle="tab" id="atm_tab" rel="tooltip"> <i class="fa fa-bank"></i>&nbsp;&nbsp; ATM </a>
		</li>
		<li>
			<a href="{{ route('balance.pane') }}" id="balance_tab" data-target="#tab_balance" data-toggle="tabajax-balance" rel="tooltip" data-investor="{{ $model->id }}"> <i class="fa fa-money"></i>&nbsp;&nbsp; Balance </a>
		</li>
		<li>
			<a href="{{ route('transaction.pane') }}" data-target="#tab_transaction" id="transaction_tab" data-toggle="tabajax-transaction" rel="tooltip"> <i class="fa fa-shopping-cart"></i>&nbsp;&nbsp; Transaction </a>
		</li>
		<li>
			<a href="{{ route('investor-asset.pane') }}" data-target="#tab_asset" id="asset_tab" data-toggle="tabajax-asset" rel="tooltip"> <i class="fa fa-cubes"></i>&nbsp;&nbsp; Asset </a>
		</li>
		<li>
			<a href="{{ route('investor-asset-fav.pane') }}"  data-target="#tab_fav_asset" id="fav_asset_tab" data-toggle="tabajax-asset-fav" rel="tooltip"> <i class="fa fa-heart"></i>&nbsp;&nbsp; Favorite Asset </a>
		</li>
		<li>
			<a href="{{ route('referral.pane', $model->id) }}"  data-target="#tab_referral" id="referral_tab" data-toggle="tabajax-referral" rel="tooltip"> <i class="fa fa-bolt"></i>&nbsp;&nbsp; Referral </a>
		</li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="tab_profile">
			@include('transaction.investor.tab.profile')
		</div>
		<div class="tab-pane" id="tab_internet_banking">
			@include('transaction.investor.tab.internet_banking')
		</div>
		<div class="tab-pane" id="tab_atm">
			@include('transaction.investor.tab.atm')
		</div>
		<div class="tab-pane" id="tab_balance">
			{{-- @include('transaction.investor.tab.balance') --}}
		</div>
		<div class="tab-pane" id="tab_transaction">
			{{-- @include('transaction.investor.tab.transaction') --}}
		</div>
		<div class="tab-pane" id="tab_asset">
			{{-- @include('transaction.investor.tab.asset') --}}
		</div>
		<div class="tab-pane" id="tab_fav_asset">
			{{-- @include('transaction.investor.tab.fav-asset') --}}
		</div>
		<div class="tab-pane" id="tab_referral"></div>
	</div>
</div>
@include('transaction.investor.script')
@include('transaction.investor.modal')
@endsection