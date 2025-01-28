@extends('layouts.global.app')

@section('title', trans('settings.settings'))

@section('sidebar')
    @include('layouts.admin.sidebare')
@endsection
@section('topbar')
    @include('layouts.admin.topbar')
@endsection

@push('css')
    <style>
        .badge {
            display: inline-block;
            padding: 0.5em 0.75em;
            font-size: 0.875em;
            color: #fff;
            background-color: #007bff;
            border-radius: 0.375em;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .badge .remove-badge {
            cursor: pointer;
            color: #fff;
            font-weight: bold;
        }

        .badge .remove-badge.left {
            margin-right: 8px;
        }

        .badge .remove-badge.right {
            margin-left: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="container settings-form">
        <h2 class="text-center">{{ trans('settings.settings') }}</h2>
        <form action="{{ route('admin.settings.updateGeneralSettings') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="android_url" class="form-label">{{ trans('settings.android url') }}</label>
                    <input type="text" id="android_url" name="android_url" @class(['form-control', 'is-invalid' => $errors->has('android_url')])
                        value="{{ old('android_url', $settings->android_url) }}"
                        placeholder="{{ trans('settings.android url') }}" dir="ltr">
                    @error('android_url')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="android_version" class="form-label">{{ trans('settings.android version') }}</label>
                    <input type="text" id="android_version" name="android_version" @class([
                        'form-control',
                        'is-invalid' => $errors->has('android_version'),
                    ])
                        value="{{ old('android_version', $settings->android_version) }}"
                        placeholder="{{ trans('settings.enter version') }}" dir="ltr">
                    @error('android_version')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="ios_url" class="form-label">{{ trans('settings.ios url') }}</label>
                    <input type="text" id="ios_url" name="ios_url" @class(['form-control', 'is-invalid' => $errors->has('ios_url')])
                        value="{{ old('ios_url', $settings->ios_url) }}" placeholder="{{ trans('settings.ios url') }}">
                    @error('ios_url')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="ios_version" class="form-label">{{ trans('settings.android version') }}</label>
                    <input type="text" id="ios_version" name="ios_version" @class(['form-control', 'is-invalid' => $errors->has('ios_version')])
                        value="{{ old('ios_version', $settings->ios_version) }}"
                        placeholder="{{ trans('settings.enter version') }}">
                    @error('ios_version')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
            </div>
            <hr class="hr">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="maintenance" class="form-label">{{ trans('settings.maintenance mode') }}</label>
                    <span @class(['maintenance', "$settings->maintenance"]) id="maintenanceStatus"
                        data-mode="{{ $settings->maintenance }}">{{ trans('settings.' . $settings->maintenance) }}</span>
                    <input type="hidden" name="maintenance" id="maintenance"
                        value="{{ old('maintenance', $settings->maintenance) }}">
                    @error('maintenance')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
            </div>
            <hr class="hr">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="keywords" class="form-label">{{ trans('settings.keywords') }}</label>
                    <div id="keywords-container" @class(['form-control', 'p-2']) style="min-height: 38px;">
                        @if (old('keywords', $settings->keywords))
                            @foreach (explode(',', old('keywords', $settings->keywords)) as $keyword)
                                <span class="badge bg-primary">
                                    {{ $keyword }}
                                    <span class="remove-badge" style="cursor: pointer;">×</span>
                                </span>
                            @endforeach
                        @endif
                    </div>
                    <input type="text" id="keywords-input" @class(['form-control', 'is-invalid' => $errors->has('keywords')])
                        placeholder="{{ trans('settings.enter keywords') }}" style="margin-top: 5px;">
                    <input type="hidden" name="keywords" id="keywords-hidden" value="{{ $settings->keywords }}">
                    @error('keywords')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
            </div>
            <hr class="hr">
            <div class="row mb-3">
                <h5>Google tag</h5>
                <div class="col-md-6">
                    <label for="google_tag_head" class="form-label">Head</label>
                    <input type="text" id="google_tag_head" name="google_tag_head" @class([
                        'form-control',
                        'is-invalid' => $errors->has('google_tag_head'),
                    ])
                        value="{{ old('google_tag_head', $settings->google_tag_head) }}" placeholder="Head">
                    @error('google_tag_head')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="google_tag_body" class="form-label">Body</label>
                    <input type="text" id="google_tag_body" name="google_tag_body" @class([
                        'form-control',
                        'is-invalid' => $errors->has('google_tag_body'),
                    ])
                        value="{{ old('google_tag_body', $settings->google_tag_body) }}" placeholder="Body">
                    @error('google_tag_body')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
            </div>
            <hr class="hr">
            <div class="row mb-3">
                <div class="col-4 row justify-content-between align-items-center mb-4">
                    <label for="site_logo" class="form-label"
                        style="margin-right: 10px;">{{ trans('settings.site logo') }}</label>
                    <label for="site_logo" class="form-label">
                        <img id="siteLogoPreview"
                            src="{{ $settings->site_logo && Storage::disk('public')->exists($settings->site_logo) ? Storage::url($settings->site_logo) : asset('img/image-file512.webp') }}"
                            alt="Site Logo" style="width: 100px; height: 100px; cursor: pointer;" />
                    </label>
                    <input type="file" @class(['form-control', 'is-invalid' => $errors->has('site_logo')]) id="site_logo" name="site_logo" accept="image/*"
                        style="display: none;" />
                    @if ($settings->site_logo && Storage::disk('public')->missing($settings->site_logo))
                        <div class="invalid-feedback d-block" role="alert">
                            <strong>
                                <i class="bi bi-exclamation-triangle-fill"></i>&nbsp;
                                {{ trans('settings.failed to load logo') }}
                            </strong>
                        </div>
                    @endif
                    @error('site_logo')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="site_name_ar" class="form-label">{{ trans('settings.site name.ar') }}</label>
                    <input type="text" id="site_name_ar" name="site_name_ar" @class(['form-control', 'is-invalid' => $errors->has('site_name_ar')])
                        placeholder="{{ trans('settings.site name.ar') }}"
                        value="{{ old('site_name_ar', $settings->site_name_ar) }}">
                    @error('site_name_ar')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="site_name_en" class="form-label">{{ trans('settings.site name.en') }}</label>
                    <input type="text" id="site_name_en" name="site_name_en" @class(['form-control', 'is-invalid' => $errors->has('site_name_en')])
                        placeholder="{{ trans('settings.site name.en') }}"
                        value="{{ old('site_name_en', $settings->site_name_en) }}">
                    @error('site_name_en')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
            </div>

            <div class="row justify-content-center">
                <button type="submit" class="col-sm-12 col-md-2 btn btn-import">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>

@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('site_logo').addEventListener('change', function(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    const output = document.getElementById('siteLogoPreview');
                    output.src = reader.result; // Update the image source to the selected image
                };
                reader.readAsDataURL(event.target.files[0]); // Read the selected file
            });

            // maintenance mode
            var statusElement = document.getElementById('maintenanceStatus');
            statusElement.addEventListener('click', function() {
                var inputElement = document.getElementById('maintenance');
                if (statusElement.dataset.mode === 'inactive') {
                    statusElement.textContent = '{{ trans('settings.active') }}';
                    statusElement.classList.replace('inactive', 'active')
                    statusElement.dataset.mode = 'active';
                    inputElement.value = 'active';
                } else {
                    statusElement.textContent = '{{ trans('settings.inactive') }}';
                    statusElement.classList.replace('active', 'inactive')
                    statusElement.dataset.mode = 'inactive';
                    inputElement.value = 'inactive';
                }
            });

            // keywords
            var keywordsInput = document.getElementById('keywords-input');
            var keywordsContainer = document.getElementById('keywords-container');
            let keywords = @json(old('keywords', $settings->keywords)).split(',').filter(Boolean);
            keywordsInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && keywordsInput.value.trim() !== '') {
                    e.preventDefault();
                    // get entered keyword
                    const keyword = keywordsInput.value.trim();

                    // add word into keywords array and display it as a badge
                    if (!keywords.includes(keyword)) {
                        keywords.push(keyword);
                        addKeywordBadge(keyword);
                    }
                    // empty input
                    keywordsInput.value = '';
                }
            });

            // function to add word as abadge
            function addKeywordBadge(keyword) {
                const badge = document.createElement('span');
                badge.classList.add('badge');
                badge.textContent = keyword;

                // create delete btn
                const removeBtn = document.createElement('span');
                removeBtn.classList.add('remove-badge', 'left');
                removeBtn.textContent = '×';
                removeBtn.addEventListener('click', function() {
                    removeKeyword(keyword, badge);
                });
                badge.appendChild(removeBtn);
                keywordsContainer.appendChild(badge);
                updateHiddenInput();
            }

            // function to delete word
            function removeKeyword(keyword, badgeElement) {
                // delete word from keywords array
                keywords = keywords.filter(k => k !== keyword);
                // delete badge from keywords array
                keywordsContainer.removeChild(badgeElement);
                updateHiddenInput();
            }

            // function to update hidden input
            function updateHiddenInput() {
                document.getElementById('keywords-hidden').value = keywords.join(',').trim(',');
                console.log(keywords.join(',').trim(','))
            }
        });
    </script>
@endpush
