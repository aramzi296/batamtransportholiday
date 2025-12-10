@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="text-center mb-5">
                <h1 class="display-4"><i class="fas fa-question-circle text-primary"></i> FAQ</h1>
                <p class="lead text-muted">Frequently Asked Questions</p>
            </div>

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
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="collapse{{ $faq->id }}" 
                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                             aria-labelledby="heading{{ $faq->id }}" 
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <div class="faq-answer">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-5x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada FAQ yang tersedia.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
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
        color: #0d6efd;
    }
</style>
@endsection







