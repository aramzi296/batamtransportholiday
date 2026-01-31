@extends('layouts.app')

@section('title', __('messages.faq.title'))

@section('content')
<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">{{ __('messages.faq.title') }}</h1>
                <p class="lead mb-4">{{ __('messages.faq.subtitle') }}</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <i class="fas fa-question-circle fa-5x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            @if($faqs->count() > 0)
                <div class="accordion" id="faqAccordion">
                    @foreach($faqs as $index => $faq)
                    <div class="accordion-item mb-3 border rounded shadow-sm">
                        <h2 class="accordion-header" id="heading{{ $faq->id }}">
                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapse{{ $faq->id }}" 
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" 
                                    aria-controls="collapse{{ $faq->id }}">
                                <i class="fas fa-question-circle text-primary me-2"></i>
                                {{ $faq->getQuestionForLocale() }}
                            </button>
                        </h2>
                        <div id="collapse{{ $faq->id }}" 
                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                             aria-labelledby="heading{{ $faq->id }}" 
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <div class="faq-answer">
                                    {!! $faq->getAnswerForLocale() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-5x text-muted mb-3"></i>
                    <p class="text-muted">{{ __('messages.faq.empty') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .bg-primary {
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    }
    .faq-answer {
        line-height: 1.8;
    }
    .faq-answer ul,
    .faq-answer ol {
        padding-left: 2rem;
        margin-bottom: 1rem;
    }
    .faq-answer li {
        margin-bottom: 0.5rem;
    }
    .accordion-button {
        font-weight: 600;
    }
    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #1e7e34;
    }
</style>
@endsection







