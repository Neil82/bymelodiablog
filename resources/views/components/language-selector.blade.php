{{-- Language Selector Component --}}
<div id="language-selector" class="language-selector">
    {{-- JavaScript will populate this --}}
</div>

{{-- language-detector.js is already included in the main app.js bundle --}}

<style>
.language-suggestion {
    animation: slideDown 0.3s ease-out;
}

.language-dropdown .language-menu {
    min-width: 200px;
}

.language-option:last-child {
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}

.language-option:first-child {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}

@keyframes slideDown {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Mobile responsive adjustments */
@media (max-width: 640px) {
    .language-dropdown .language-menu {
        left: 0;
        right: auto;
        min-width: 180px;
    }
    
    .language-suggestion {
        padding: 1rem;
    }
    
    .language-suggestion .flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .language-suggestion .flex:last-child {
        flex-direction: row;
        align-items: center;
        align-self: flex-end;
    }
}
</style>