/* global jQuery */

(function($){
    'use strict';

    $.fn.modalSteps = function(options){
        var $modal = this;

        var settings = $.extend({
            btnCancelHtml: 'Cancel',
            btnPreviousHtml: 'Back',
            btnNextHtml: 'Next',
            btnLastStepHtml: 'Finish',
            disableNextButton: false,
            completeCallback: function(){
				submitSetup();
			},
            callbacks: {}
        }, options);


        var validCallbacks = function(){
            var everyStepCallback = settings.callbacks['*'];

            if (everyStepCallback !== undefined && typeof(everyStepCallback) !== 'function'){
                throw 'everyStepCallback is not a function! I need a function';
            }

            if (typeof(settings.completeCallback) !== 'function') {
                throw 'completeCallback is not a function! I need a function';
            }

            for(var step in settings.callbacks){
                if (settings.callbacks.hasOwnProperty(step)){
                    var callback = settings.callbacks[step];

                    if (step !== '*' && callback !== undefined && typeof(callback) !== 'function'){
                        throw 'Step ' + step + ' callback must be a function';
                    }
                }
            }
        };

        var executeCallback = function(callback){
            if (callback !== undefined && typeof(callback) === 'function'){
                callback();
                return true;
            }
            return false;
        };

        $modal
            .on('show.bs.modal', function(){
                var $modalFooter = $modal.find('.modal-footer'),
                    $btnCancel = $modalFooter.find('.js-btn-step[data-orientation=cancel]'),
                    $btnPrevious = $modalFooter.find('.js-btn-step[data-orientation=previous]'),
                    $btnNext = $modalFooter.find('.js-btn-step[data-orientation=next]'),
                    everyStepCallback = settings.callbacks['*'],
                    stepCallback = settings.callbacks['1'],
                    actualStep,
                    $actualStep,
                    titleStep,
                    $titleStepSpan,
                    nextStep;

                if (settings.disableNextButton){
                    $btnNext.attr('disabled', 'disabled');
                }
                $btnPrevious.attr('disabled', 'disabled');

                validCallbacks();
                executeCallback(everyStepCallback);
                executeCallback(stepCallback);

                // Setting buttons
                $btnCancel.html(settings.btnCancelHtml);
                $btnPrevious.html(settings.btnPreviousHtml);
                $btnNext.html(settings.btnNextHtml);

                $actualStep = $('<input>').attr({
                    'type': 'hidden',
                    'id': 'actual-step',
                    'value': '1',
                });

                $modal.find('#actual-step').remove();
                $modal.append($actualStep);

                actualStep = 1;
                nextStep = actualStep + 1;

                $modal.find('[data-step=' + actualStep + ']').removeClass('hide');
                $btnNext.attr('data-step', nextStep);

                titleStep = $modal.find('[data-step=' + actualStep + ']').data('title');
                $titleStepSpan = $('<span>')
                                    .addClass('label label-primary')
                                    .html(actualStep);

                $modal
                    .find('.js-title-step')
                    .append($titleStepSpan)
                    .append(' ' + titleStep);
            })
            .on('hidden.bs.modal', function(){
                var $actualStep = $modal.find('#actual-step'),
                    $btnNext = $modal.find('.js-btn-step[data-orientation=next]');

                $modal
                    .find('[data-step]')
                    .not($modal.find('.js-btn-step'))
                    .addClass('hide');

                $actualStep
                    .not($modal.find('.js-btn-step'))
                    .remove();

                $btnNext
                    .attr('data-step', 1)
                    .html(settings.btnNextHtml);

                $modal.find('.js-title-step').html('');
            });

        $modal.find('.js-btn-step').on('click', function(){
            var $btn = $(this),
                $actualStep = $modal.find('#actual-step'),
                $btnPrevious = $modal.find('.js-btn-step[data-orientation=previous]'),
                $btnNext = $modal.find('.js-btn-step[data-orientation=next]'),
                $title = $modal.find('.js-title-step'),
                orientation = $btn.data('orientation'),
                actualStep = parseInt($actualStep.val()),
                everyStepCallback = settings.callbacks['*'],
                steps,
                nextStep,
                $nextStep,
                newTitle;

            steps = $modal.find('div[data-step]').length;

            // Callback on Complete
            if ($btn.attr('data-step') === 'complete'){
                settings.completeCallback();
                $modal.modal('hide');

                return;
            }

            // Check the orientation to make logical operations with actualStep/nextStep
            if (orientation === 'next'){
                nextStep = actualStep + 1;

                $btnPrevious.attr('data-step', actualStep);
                $actualStep.val(nextStep);

            } else if (orientation === 'previous'){
                nextStep = actualStep - 1;

                $btnNext.attr('data-step', actualStep);
                $btnPrevious.attr('data-step', nextStep - 1);

                $actualStep.val(actualStep - 1);

            } else {
                $modal.modal('hide');
                return;
            }

            if (parseInt($actualStep.val()) === steps){
                $btnNext
                    .attr('data-step', 'complete')
                    .html(settings.btnLastStepHtml);
            } else {
                $btnNext
                    .attr('data-step', nextStep)
                    .html(settings.btnNextHtml);
            }

            if (settings.disableNextButton){
                $btnNext.attr('disabled', 'disabled');
            }

            // Hide and Show steps
            $modal
                .find('[data-step=' + actualStep + ']')
                .not($modal.find('.js-btn-step'))
                .addClass('hide');

            $modal
                .find('[data-step=' + nextStep + ']')
                .not($modal.find('.js-btn-step'))
                .removeClass('hide');

            // Just a check for the class of previous button
            if (parseInt($btnPrevious.attr('data-step')) > 0 ){
                $btnPrevious.removeAttr('disabled');
            } else {
                $btnPrevious.attr('disabled', 'disabled');
            }

            if (orientation === 'previous'){
                $btnNext.removeAttr('disabled');
            }

            // Get the next step
            $nextStep = $modal.find('[data-step=' + nextStep + ']');

            // Verify if we need to unlock continue btn of the next step
            if ($nextStep.attr('data-unlock-continue')){
                $btnNext.removeAttr('disabled');
            }

            // Set the title of step
            newTitle = $nextStep.attr('data-title');
            var $titleStepSpan = $('<span>')
                                .addClass('label label-primary')
                                .html(nextStep);

            $title
                .html($titleStepSpan)
                .append(' ' + newTitle);

            var stepCallback = settings.callbacks[$actualStep.val()];
            executeCallback(everyStepCallback);
            executeCallback(stepCallback);
        });

        return this;
    };
}(jQuery));
