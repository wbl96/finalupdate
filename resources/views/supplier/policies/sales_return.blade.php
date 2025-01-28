@extends('layouts.global.app')

@section('title', trans('policies.suppliers.sale & return policy'))

@section('sidebar')
    @include('layouts.supplier.sidebare')
@endsection
@section('topbar')
    @include('layouts.supplier.topbar')
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <style>
        .ql-toolbar.ql-snow {
            direction: ltr
        }
    </style>
@endpush

@section('content')
    {{-- Rich Text Editor Container --}}
    <div class="container mt-4">
        <h5 class="mb-3">{{ trans('policies.suppliers.sale & return policy') }}</h5>
        <form action="{{ $formAction }}" method="POST" id="policyForm">
            @csrf
            @method($formMethod)
            <div id="editor-container" style="height: 500px;"></div>
            <textarea name="policy" id="policyContent" class="d-none">{!! $policyContent ?? null !!}</textarea>
            {{-- Save Button --}}
            <div class="mt-3 text-center">
                <button class="btn  btn-import" style="width: 150px;" id="saveBtn">{{ trans('global.save') }}</button>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill editor
            const quill = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: 'Start typing here...',
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, 3, 4, 5, 6, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['link', 'image'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }],
                        [{
                            'direction': 'rtl'
                        }],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }], // dropdown with defaults from theme
                        [{
                            'font': []
                        }],
                        [{
                            'align': []
                        }],
                        ['clean'], // Remove formatting button
                    ],
                },
            });

            quill.root.innerHTML = document.querySelector('#policyContent').value;

            // Save Button Event Listener
            document.getElementById('policyForm').addEventListener('submit', function(evt) {
                evt.preventDefault();
                // select editor container and get inner html
                const editorContent = quill.root.innerHTML;
                // select textarea to put content
                const textarea = document.querySelector('#policyContent');
                // put editor content into textarea
                textarea.value = editorContent;
                // submit form
                this.submit();
            });
        });
    </script>
@endpush
