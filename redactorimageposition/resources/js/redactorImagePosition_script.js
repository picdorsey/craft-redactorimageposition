/**
 * Redactor Image Position plugin for Craft CMS
 *
 * Redactor Image Position JS
 *
 * @author    Piccirilli Dorsey, Inc. (Nicholas O'Donnell)
 * @copyright Copyright (c) 2016 Piccirilli Dorsey, Inc. (Nicholas O'Donnell)
 * @link      http://picdorsey.com
 * @package   RedactorImagePosition
 * @since     1.0.3
 */

if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.imagePosition = function () {
    return {
        init: function () {
            this.imagePosition.figureClasses = figureConfig;

            this.imagePosition.positionTemplate = String()
            + '<section id="redactor-image-position">'
            + '    <label>Position</label>'
            + '    <div class="btngroup big" id="redactor-image-position-field">'
            + '        <div title="Left" class="btn big" data-icon="posleft" data-option="left"></div>'
            + '        <div title="Full" class="btn big active" data-icon="posfull" data-option="full"></div>'
            + '        <div title="Right" class="btn big" data-icon="posright" data-option="right"></div>'
            + '    </div>'
            + '    <input type="hidden" id="redactor-image-position" name="redactor-image-position-field">'
            + '</section>'
            + '<script>var $buttons=$("#redactor-image-position-field div"),$position=$("#redactor-image-position");$buttons.on("click",function(){var t=$(this),i=t.data("option");return $buttons.removeClass("active"),t.addClass("active"),$position.val(i),!1});</script>';

            this.imagePosition.events();
        },

        events: function () {
            $('body').on('click', '.elementselectormodal .submit', this.imagePosition.onModalSave.bind(this));
            $('body').on('click', '#redactor-modal-button-action', this.imagePosition.onModalSave.bind(this));

            this.core.element().on('modalOpened.callback.redactor', this.imagePosition.onModalOpen.bind(this));
        },

        onModalOpen: function (name, modal) {
            if (name != 'image-edit') {
                return;
            }

            var $positionSection = $(this.imagePosition.positionTemplate);
            var $positions = $positionSection.find('.btn');
            var $redactorImage = $('#redactor-modal img');
            var src = $redactorImage.attr('src');
            var $bodyImage = $('.redactor-box img[src*="' + src + '"]');
            var $figure = $bodyImage.closest('figure');

            // get existing figure position
            if ($figure.hasClass(this.imagePosition.figureClasses['figureLeft'])) {
                $bodyImage.data('pos', 'left');
            } else if ($figure.hasClass(this.imagePosition.figureClasses['figureRight'])) {
                $bodyImage.data('pos', 'right');
            } else if ($figure.hasClass(this.imagePosition.figureClasses['figureFull'])) {
                $bodyImage.data('pos', 'full');
            }

            var pos = $bodyImage.data('pos') || 'full';
            var $activePosition = $positions.filter('[data-option*="' + pos +'"]');

            $positions.removeClass('active');
            $activePosition.addClass('active');

            // insert position element
            $('#redactor-modal section:last-child').prepend($positionSection);
        },

        onModalSave: function () {
            var pos = $('#redactor-image-position').val();
            var $redactorImage = $('#redactor-modal img');
            var src = $redactorImage.attr('src');
            var $bodyImage = $('.redactor-box img[src*="' + src + '"]');
            var $images = $('.redactor-box img');

            $bodyImage.data('pos', pos);

            $images.each(this.imagePosition.formatImage.bind(this));
        },

        formatImage: function (index, elem) {
            var $image = $(elem);
            var c = $image.data('pos');
            var $caption = ($image.next('figcaption').length > 0) ? $image.next('figcaption') : $image.parent().next('figcaption');
            var $figure = $image.closest('figure');

            if (c !== 'left' && c !== 'right' && c !== 'full') return;

            if ($figure.length === 0) {
                // wrap in <figure> element if not already wrapped
                $image.wrap(this.imagePosition.figureClasses['figureWrap']);
                $figure = $image.parent('figure').unwrap();
            } else {
                // remove existing positioning on figure
                $figure.removeClass(this.imagePosition.figureClasses['figureLeft']);
                $figure.removeClass(this.imagePosition.figureClasses['figureRight']);
                $figure.removeClass(this.imagePosition.figureClasses['figureFull']);
            }

            $image.addClass(this.imagePosition.figureClasses['imageClass']);
            $caption.addClass(this.imagePosition.figureClasses['captionClass']);

            $figure.append($caption);

            if (c === 'left') $figure.addClass(this.imagePosition.figureClasses['figureLeft']);
            if (c === 'right') $figure.addClass(this.imagePosition.figureClasses['figureRight']);
            if (c === 'full') $figure.addClass(this.imagePosition.figureClasses['figureFull']);
        }
    };
};

var redactorImagePosition = {
    init: function () {
        this.makeConfigEditable();
    },

    makeConfigEditable: function () {
        $('.js-make-enabled textarea').removeAttr('disabled');
    }
};

$(redactorImagePosition.init.bind(redactorImagePosition));
