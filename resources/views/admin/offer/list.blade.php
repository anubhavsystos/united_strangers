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
                <tr>
                    <td> {{$num++}} </td>
                    <td><img src="{{ $offer['image'] ?? '' }}" width="50" height="50" class="rounded" alt=""></td>
                    <td> {{ $offer['title'] ?? '' }}</td>                  
                    <td>{{ isset($offer['to_date']) ? \Carbon\Carbon::parse($offer['to_date'])->format('d M Y') : '' }}</td>
                    <td>{{ isset($offer['from_date']) ? \Carbon\Carbon::parse($offer['from_date'])->format('d M Y') : '' }}</td>                    
                    <td> {{ \Illuminate\Support\Str::limit($offer['description'] ?? '', 10, '...') }}</td>
                    <td>{{ $offer['segment_type'] ?? '' }}</td>
                    <td>{{ $offer['visibility'] ?? '' }}</td>
                    <td>{{ $offer['name'] ?? '' }}</td>
                    <td>{{ isset($offer['created_at']) ? \Carbon\Carbon::parse($offer['created_at'])->format('d M Y') : '' }}</td>
                    <td> 
                        <div class="dropdown ol-icon-dropdown">
                            <button class="px-2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="fi-rr-menu-dots-vertical"></span>
                            </button>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item fs-14px" href="{{ route('admin.offer.edit', $offer['id']) }}"> {{get_phrase('Edit')}} </a></li>
                              <li><a class="dropdown-item fs-14px" onclick="delete_modal('{{route('admin.offer.delete',$offer['id'])}}')" href="javascript:void(0);"> {{get_phrase('Delete')}} </a></li>

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