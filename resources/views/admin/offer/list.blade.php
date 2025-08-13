@extends('layouts.admin')
@section('title', get_phrase('Offers List'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-12px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Offers Lists') }}
            </h4>

            <a href="{{route('admin.offer.create')}}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                <span class="fi-rr-plus"></span>
                <span> {{get_phrase('Add New offer')}} </span>
            </a>
        </div>
    </div>
</div>

<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        @if(!empty($offers) && count($offers)!=0)
        <table id="datatable" class=" table nowrap w-100">
            <thead>
                <tr>
                    <th> {{get_phrase('ID')}} </th>
                    <th> {{get_phrase('Image')}} </th>
                    <th> {{get_phrase('Title')}} </th>
                    <th> {{get_phrase('To Date')}} </th>                    
                    <th> {{get_phrase('From Date')}} </th>                    
                    <th> {{get_phrase('Description')}} </th>                    
                    <th> {{get_phrase('Offer Segment')}} </th>                    
                    <th> {{get_phrase('Offer On')}} </th>
                    <th> {{get_phrase('Visibility')}} </th>
                    <th> {{get_phrase('Created at')}} </th>
                    <th> {{get_phrase('Action')}} </th>
                </tr>
            </thead>
            <tbody>
                @php $num = 1 @endphp
                @foreach ($offers as $offer)
                @php $image = json_decode($offer->image)[0]??0; @endphp    
                <tr>
                    <td> {{$num++}} </td>
                    <td>
                        <img src="{{get_all_image('offer-images/'.json_decode($offer->image)[0]??0)}}" width="50" height="50" class="rounded" alt="">
                    </td>
                    <td> {{$offer->title}} 
                        @if(isset($claimStatus) && $claimStatus->status == 1) 
                        <svg data-bs-toggle="tooltip" 
                        data-bs-title=" {{ get_phrase('This offer is verified') }}" fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="paint0_linear_16_1334" gradientUnits="userSpaceOnUse" x1="12" x2="12" y1="-1.2" y2="25.2"><stop offset="0" stop-color="#ce9ffc"/><stop offset=".979167" stop-color="#7367f0"/></linearGradient><path d="m3.783 2.826 8.217-1.826 8.217 1.826c.2221.04936.4207.17297.563.3504.1424.17744.22.39812.22.6256v9.987c-.0001.9877-.244 1.9602-.7101 2.831s-1.14 1.6131-1.9619 2.161l-6.328 4.219-6.328-4.219c-.82173-.5478-1.49554-1.2899-1.96165-2.1605-.46611-.8707-.71011-1.8429-.71035-2.8305v-9.988c.00004-.22748.07764-.44816.21999-.6256.14235-.17743.34095-.30104.56301-.3504zm8.217 10.674 2.939 1.545-.561-3.272 2.377-2.318-3.286-.478-1.469-2.977-1.47 2.977-3.285.478 2.377 2.318-.56 3.272z" fill="url(#paint0_linear_16_1334)"/></svg>
                         @endif 
                    </td>                  
                    <td>{{ isset($offer->to_date) ? \Carbon\Carbon::parse($offer->to_date)->format('d M Y') : '' }}</td>
                    <td>{{ isset($offer->from_date) ? \Carbon\Carbon::parse($offer->from_date)->format('d M Y') : '' }}</td>
                    <td>{{ $offer->description ?? '' }}</td>
                    <td>{{ $offer->segment_type ?? '' }}</td>
                    <td>{{ $offer->visibility ?? '' }}</td>
                    <td>{{ $offer->segment->name ?? '' }}</td>
                    <td>{{ isset($offer->created_at) ? $offer->created_at->format('d M Y, h:i A') : '' }}</td>
                    <td> 
                        <div class="dropdown ol-icon-dropdown">
                            <button class="px-2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="fi-rr-menu-dots-vertical"></span>
                            </button>
                            <ul class="dropdown-menu">
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            @include('layouts.no_data_found')
        @endif
    </div>
</div>

@endsection