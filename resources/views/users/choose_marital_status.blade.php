@extends('mobile::common.base_layout')

@section('title', 'EngageMobile - Profile')

@section('styles')
    <link rel="stylesheet" href="{{ getAssetCss('common/main.css') }}" />
@endsection

@section('content')
    <section id="profile" data-url-route-home-login="{{ route('mobile.home.top_login') }}"
        data-api-master="{{ route('api.mobile.area.getPrefectureCodeMaster') }}"
        data-url-birth-year="{{ route('mobile.register.profile.birth_year') }}">

        <input type="hidden" name="is_first" value="{{ request()->get('is_first', '') }}">
        <div class="container container--form profile margin-40-ios" id="container_form_profile">
            <div class="messageArea">
                <div class="main text-break ">Marital Status</div>
            </div>

            <div class="border ej-border-error ej-bg-warning round-15 mt-3 px-4 py-3 mb-3 d-none" id="error-message">
                <ul class="list-unstyled mb-0 ">
                    <li class="ej-text-error f-12 f-12-before fw-bold cause icomoon list-warning ">Please Enter Valid Name
                    </li>
                </ul>
            </div>

            <div class="form-profile">
                <div class="row">
                    <div class="col-12 my-2 d-flex justify-content-center">
                        <a id="update-status" href="#"
                            class="btn btn-lg btn-engage no-shadow
                            btn-engage--submit ej-border-light-grey bg-white icomoon icomoon-small-next-after">Single</a>
                    </div>
                    <div class="col-12 my-2 d-flex justify-content-center">
                        <a id="show-input" href="#"
                            class="btn btn-lg btn-engage no-shadow
                            btn-engage--submit ej-border-light-grey bg-white icomoon icomoon-small-next-after">Married</a>
                    </div>
                </div>
            </div>
            <div class="other-area-form" id="spouse-input" style="display: none;">
                <div class="school-form">
                    <div class="round-10 bg-white ej-border-grey border p-3 bg-white">
                        <div class="searchArea">
                            <div class="position-relative">
                                <label for="spouse-name"
                                    class="col-12 my-2 d-flex justify-content-center main text-break"></label>

                                <input class="input-text input-100 form-control dropdown_input" name="spouse-name"
                                    id="spouse-name" type="text" placeholder="Enter Name">
                                <div class="redisplay display-none">学校名候補の再表示</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-5 submit-status ">
                        <button
                            class="next-btn btn btn-lg no-shadow btn-engage btn-engage-lg w-50 ej-bg-main text-light f-16  "
                            id="submitBtn">Submit</button>
                    </div>
                </div>
            </div>
    </section>

    @if (isset($modal) && $modal['display'] && $modal['signup'])
        @include('mobile::register.profile.partials.modal_success')
    @endif

@endsection

@section('scripts')
    <script src="{{ getAssetJs('register/profile/materials_status.js') }}"></script>
    <script>
        import {
            alertNetworkError,
            isOnlineMode,
            hideKeyboard
        } from "../../common/utils";
        import {
            Native
        } from '../../common/native';
        import karte from '@mobile/common/karte';

        // Initialize variables and get URLs
        let url_birth_year = $("#profile").data("url-birth-year");
        let url_materials_status = $("#profile").data("url-materials-status");
        let sex = localStorage.getItem('sex');

        // Initialize DOM elements
        let $submitBtn = $("#submitBtn");
        let $errorMessage = $("#error-message");

        // Add initial class and hide error message
        $submitBtn.addClass("disabled");
        $errorMessage.addClass("d-none");

        // Event handling for the 'input' event on the spouse name input
        $('#spouse-name').on('input', function() {
            let spouseName = $(this).val().trim();
            let isValid = validateSpouseName(spouseName);

            if (isValid) {
                $submitBtn.removeClass("disabled");
                $errorMessage.addClass("d-none");
            } else {
                $submitBtn.addClass("disabled");
                $errorMessage.removeClass("d-none");
            }
        });

        // Event handling for the 'click' event on the 'Show Input' button
        $("#show-input").on("click", function() {
            $('#spouse-input').show();

            // Remove selected color
            $('.container--form').find('.btn-engage').removeClass('ej-bg-light');
            // Change color of new selected
            $(this).addClass('ej-bg-light');

            let label = (sex === '1') ? "Wife's Name:" : "Husband's Name:";
            $("#spouse-input label").text(label);
        });

        // Event handling for the 'blur' event on the spouse name input
        $('#spouse-name').blur(function() {
            let spouseName = $(this).val().trim();
            let isValid = validateSpouseName(spouseName);

            if (isValid) {
                $submitBtn.removeClass("disabled");
                $errorMessage.addClass("d-none");
            } else {
                $submitBtn.addClass("disabled");
                $errorMessage.removeClass("d-none");
            }
        });

        // Event handling for the 'click' event on the 'Submit' button
        $("#submitBtn").on("click", function() {
            let spouseName = $("#spouse-name").val();

            if (!validateSpouseName(spouseName)) {
                $errorMessage.text('Please enter a valid name.');
                return;
            } else {
                $errorMessage.text('');
            }

            let storageKey = (sex === '1') ? 'wife_name' : 'husband_name';
            localStorage.setItem('marital_status', 'married');
            localStorage.setItem(storageKey, spouseName);

            alert('Status updated successfully.');
            navigateToNextPage();
        });

        // Event handling for the 'click' event on the 'Update Status' button
        $("#update-status").on("click", function() {
            $('#spouse-input').hide();
            let status = "single";
            localStorage.setItem('status', status);

            // Remove selected color
            $('.container--form').find('.btn-engage').removeClass('ej-bg-light');
            // Change color of new selected
            $(this).addClass('ej-bg-light');

            alert('Status updated successfully.');
            navigateToNextPage();
        });

        // Function to navigate to the next page
        function navigateToNextPage() {
            location.href = url_birth_year;
        }

        // Function to validate the spouse name
        function validateSpouseName(name) {
            return name && name.length >= 4 && isNaN(name);
        }
    </script>

@endsection
