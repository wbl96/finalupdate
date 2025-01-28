@if (session()->has('error') || session()->has('success') || $errors->any())
    <section class="ad a1H a1I[100px] f y dg ud" id="alerts-container">
        @if (session()->has('error'))
            <div class="alert alert-danger text-center card-body col-8" style="margin-left: auto;margin-right: auto;">
                <span>{!! session('error') !!}</span>
            </div>
        @endif

        @if (session()->has('success'))
            <div class="alert alert-success text-center card-body col-8" style="margin-left: auto;margin-right: auto;">
                <span>{!! session('success') !!}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger card-body col-8" style="margin-left: auto;margin-right: auto;">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </section>
@endif

