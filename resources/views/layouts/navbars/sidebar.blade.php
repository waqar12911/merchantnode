<div class="sidebar">
    <div class="sidebar-wrapper">
  @if(\Illuminate\Support\Facades\Auth::user()->type=='beta')
        <div class="logo">
            <a href="#" class="simple-text logo-mini">{{ __('') }}</a>
            <a href="#" class="simple-text logo-normal">{{ __('Merchant Node') }}</a>
        </div>
        <ul class="nav">
            <li @if (isset($pageSlug) && $pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            
            <li @if (isset($pageSlug) && $pageSlug == 'transactions') class="active " @endif>
                <a href="{{ route('getTransactions') }}">
                    <i class="tim-icons icon-bank"></i>
                    <p>{{ __('Transactions') }}</p>
                </a>
            </li>
            
            
            
           
    </ul>
     @endif

  @if(\Illuminate\Support\Facades\Auth::user()->type == 'alpha')
         <div class="logo">
            <a href="#" class="simple-text logo-mini">{{ __('') }}</a>
            <a href="#" class="simple-text logo-normal">{{ __(auth()->user()->name) }}</a>
        </div>
        <ul class="nav">
            <li @if (isset($pageSlug) && $pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li @if (isset($pageSlug) && $pageSlug == 'transactions alpha') class="active " @endif>
                <a href="{{ route('getTransactionsalpha') }}">
                    <i class="tim-icons icon-bank"></i>
                    <p>{{ __('Transactions') }}</p>
                </a>
            </li>
            @if (isset($pageSlug) && $pageSlug == 'transactions alpha')
             <li >
                <a href="{{ route('delete_transections') }}" onclick="return confirm('Are you sure to delete all transactions?')">
                    <i class="tim-icons icon-alert-circle-exc"></i>
                    <p>{{ __('Delete all transactions') }}</p>
                </a>
            </li>
            @endif
        </ul>
  @endif
    
    </div>
</div>
