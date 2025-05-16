<div class="modal fade" id="showProductModal{{ $product->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showProductModalLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showProductModalLabel{{ $product->id }}">تفاصيل المنتج</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        {{-- معرض الصور --}}
                        <div id="productImagesCarousel{{ $product->id }}" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($product->images as $key => $image)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image->path) }}" class="d-block w-100 rounded"
                                            alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>
                            @if ($product->images->count() > 1)
                                <a class="carousel-control-prev" href="#productImagesCarousel{{ $product->id }}"
                                    role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">السابق</span>
                                </a>
                                <a class="carousel-control-next" href="#productImagesCarousel{{ $product->id }}"
                                    role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">التالي</span>
                                </a>
                            @endif
                        </div>

                        {{-- الفيديو --}}
                        @if ($product->video_url)
                            <div class="mt-4">
                                <h5>فيديو المنتج:</h5>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="{{ $product->video_url }}"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h3>{{ $product->name }}</h3>
                        <p class="text-muted">القسم: {{ $product->subCategory->name }}
                            ({{ $product->subCategory->category->name }})</p>
                        <hr>
                        <h5>الماركة:</h5>
                        <p>{{ $product->brand->name }}</p>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>السعر:</h5>
                                @if ($product->discount_percent > 0)
                                    <p class="text-danger"><del>{{ number_format($product->price, 2) }} </del></p>
                                    <p class="text-success h4">{{ number_format($product->price_after_discount, 2) }}
                                        </p>
                                    <span class="badge badge-success">وفر {{ $product->discount_percent }}%</span>
                                @else
                                    <p class="text-primary h4">{{ number_format($product->price, 2) }} </p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5>الموقع:</h5>
                                <p>{{ $product->city }} - {{ $product->neighborhood }}</p>
                                @if ($product->latitude && $product->longitude)
                                    <div id="productMap{{ $product->id }}" style="height: 150px; width: 100%;"></div>
                                @else
                                    <p class="text-muted">لا يوجد موقع محدد</p>
                                @endif
                            </div>
                        </div>

                        <hr>
                        <h5>وصف المنتج:</h5>
                        <p>{{ $product->description ?? 'لا يوجد وصف' }}</p>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>تاريخ الإنشاء:</strong> {{ $product->created_at->format('Y-m-d') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>آخر تحديث:</strong> {{ $product->updated_at->format('Y-m-d') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@if ($product->latitude && $product->longitude)
    @push('scripts')
        <script>
            function initMap{{ $product->id }}() {
                var location = {
                    lat: {{ $product->latitude }},
                    lng: {{ $product->longitude }}
                };
                var map = new google.maps.Map(document.getElementById('productMap{{ $product->id }}'), {
                    zoom: 15,
                    center: location
                });
                var marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
            }
        </script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap{{ $product->id }}">
        </script>
    @endpush
@endif
