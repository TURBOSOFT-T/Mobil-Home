
@extends('front.fixe')
@section('titre', $service->nom)
@section('body')
    <main>







      <!-- service details area start -->
      <div class="tp-sv-details-area fix pt-150 pb-145">
         <div class="container">
            <div class="row">
               <div class="col-xl-7 col-lg-7">
                  <div class="tp-sv-details-wrapper">
                     <h4 class="tp-section-title mb-20">{{$service->nom}}</h4>
                     <div class="tp-service-thumb">
                        <img src="{{ Storage::url($service->image) }}" width="400 " height="400 "
                        class="rounded shadow"  alt="">
                     </div>
                  
                   
                     <div class="row gx-30">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                           <div class="tp-sv-details-banner">
                              <img src="assets/img/service/details-1-2.jpg" alt="">
                           </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                           <div class="tp-sv-details-banner">
                              <img src="assets/img/service/details-1-3.jpg" alt="">
                           </div>
                        </div>
                     </div>
                     <div class="tp-sv-details-text mt-50 mb-50">
                        <h3 class="tp-sv-details-title mb-20">
                            Description
                        </h3>
                        <p>{{$service->description}}</p>
                     </div>
                   
                  </div>
               </div>
               <div class="col-xl-5 col-lg-5">
                  <div class="tp-sv__sidebar-wrapper">
                     <div class="tp-sv__sidebar-widget mb-50">
                        <h4 class="tp-sv__sidebar-title mb-35"> Les autres Services</h4>
                        <div class="tp-sv__sidebar-widget-content">
                           
                            <ul>
                                @foreach ($services as $service )
                                <li class="active">
                                   <a href="{{ route('details-services', ['id' => $service->id, 'slug'=>Str::slug(Str::limit($service->nom, 10))]) , }}">
                                     {{$service->nom}}
                                      <span>
                                         <svg width="15" height="10" viewBox="0 0 15 10" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                               d="M14.1543 4.99974L9.5111 9.644L8.7559 8.88987L12.1127 5.53307H0.0668316V4.4664H12.1127L8.7559 1.11067L9.5111 0.355469L14.1543 4.99974Z"
                                               fill="currentcolor" />
                                         </svg>
                                      </span>
                                   </a>
                                </li>
                                @endforeach
                               
                             
                             </ul>
                           
                        
                        </div>
                     </div>
                    
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- service details area end -->

    </main>
    @endsection
    