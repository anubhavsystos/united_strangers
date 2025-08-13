@extends('layouts.admin')
@section('title', get_phrase('Claimed Listings'))
@section('admin_layout')

<div class="ol-card radius-8px">
    <div class="ol-card-body my-2 py-18px px-20px">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
            <h4 class="title fs-16px">
                <i class="fi-rr-settings-sliders me-2"></i>
                {{ get_phrase('Claimed Listings') }}
            </h4>
        </div>
    </div>
</div>

<div class="ol-card mt-3">
    <div class="ol-card-body p-3">
        @if(count($listings) != 0)
        <table id="datatable" class="table nowrap w-100">
            <thead>
                <tr>
                    <th> {{get_phrase('ID')}} </th>
                    <th> {{get_phrase('Listings')}} </th>
                    <th> {{get_phrase('Listing Owner')}} </th>
                    <th> {{get_phrase('Additional Information')}} </th>
                    <th> {{get_phrase('Listings Type')}} </th>
                    <th> {{get_phrase('Status')}} </th>
                    <th> {{get_phrase('Action')}} </th>
                </tr>
            </thead>
            <tbody>
                @php $num = 1;  @endphp
                @foreach ($listings as $listing)                
                <tr>
                    <td> {{$num++}}.</td>
                    <td>
                        <div class="dAdmin_profile d-flex flex-wrap  min-w-200px gap-2">
                            <div class="dAdmin_profile_name">
                                <p class=" fs-14px">
                                    <a href="">{{$listing['listing_data']['title'] ?? 'N/A'}}</a>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td> 
                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                            <div class="dAdmin_profile_name">
                                <p class="fs-14px mb-1"> {{$listing['user']['name'] ?? 'N/A'}} </p>
                            </div>
                        </div>
                     </td>
                    <td> 
                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                            <div class="dAdmin_profile_name">
                                <p class="fs-14px mb-1"> {{get_phrase('Name')}}: {{$listing['user']['name'] ?? 'N/A'}} </p>
                                <p class="sfs-14px mb-1"> {{get_phrase('Phone')}}: {{$listing['user']['phone'] ?? 'N/A'}}  </p>
                                <p class="fs-14px">  {{$listing['additional_info'] ?? 'N/A'}}  </p>  
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                            <div class="dAdmin_profile_name">
                                <p class=" capitalize">  {{$listing['listing_type'] ?? 'N/A'}}  </p>
                            </div>
                        </div> 
                    </td>
                    <td>
                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                            <div class="dAdmin_profile_name">
                                @if($listing['status'] == 1)
                                <p class="sub-title2 text-12px badge bg-success"> {{get_phrase('Approve')}}</p>
                                @else
                                <p class="sub-title2 text-12px badge bg-warning"> {{get_phrase('Pending')}}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                  
                    <td> 
                        <div class="dropdown ol-icon-dropdown">
                            <button class="px-2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="fi-rr-menu-dots-vertical"></span>
                            </button>
                            <ul class="dropdown-menu">
                            <li><a class="dropdown-item fs-14px" href="{{route('listing.details',['id'=>$listing['id'],'type'=>$listing['listing_type'], 'slug'=>$listing['listing_data']['title']])}}" target="_blank"> {{get_phrase('View frontend')}} </a></li>
                            <li><a class="dropdown-item fs-14px" onclick="confirm_modal('{{route('admin.claim-listing.approve',['type' => $listing['listing_type'], 'listing_id'=>$listing['id']])}}')" href="javascript:;"> {{get_phrase('Approve')}} </a></li>
                            <li><a class="dropdown-item fs-14px" onclick="delete_modal('{{route('admin.claim-listing.delete',[ 'id'=>$listing['id']])}}')" href="javascript:void(0);"> {{get_phrase('Delete')}} </a></a></li>
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