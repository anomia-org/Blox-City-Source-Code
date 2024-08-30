

var navbarMoreDropdown = '[data-toggle="topbar-more-dropdown"]';
var navbarUserDropdown = '[data-toggle="topbar-user-dropdown"]';
var navbarUserDropdownMobile = '[data-toggle="topbar-user-dropdown-mobile"]';

$(document).ready(function() {
    $(navbarMoreDropdown).click(function(event) {
        var target = event.target;

        if ($('#topbar-more-dropdown').is(':hidden')) {
            if (targetMatches(true, target, `${navbarMoreDropdown}, ${navbarMoreDropdown} *`)) {
                $(navbarMoreDropdown).addClass('active');
                $('#topbar-more-dropdown').slideDown(300);
            }
        } else if ($('#topbar-more-dropdown').is(':visible')) {
            if (targetMatches(false, target, '#topbar-more-dropdown, #topbar-more-dropdown *')) {
                $(navbarMoreDropdown).removeClass('active');
                $('#topbar-more-dropdown').slideUp(300);
            }
        }
    });

    $(navbarUserDropdown).click(function(event) {
        var target = event.target;

        if ($('#topbar-user-dropdown').is(':hidden')) {
            if (targetMatches(true, target, `${navbarUserDropdown}, ${navbarUserDropdown} *`)) {
                $(navbarUserDropdown).addClass('active');
                $('#topbar-user-dropdown').slideDown(300);
            }
        } else if ($('#topbar-user-dropdown').is(':visible')) {
            if (targetMatches(false, target, '#topbar-user-dropdown, #topbar-user-dropdown *')) {
                $(navbarUserDropdown).removeClass('active');
                $('#topbar-user-dropdown').slideUp(300);
            }
        }
    });

    $(navbarUserDropdownMobile).click(function(event) {
        var target = event.target;

        if ($('#topbar-user-dropdown-mobile').is(':hidden')) {
            if (targetMatches(true, target, `${navbarUserDropdownMobile}, ${navbarUserDropdownMobile} *`)) {
                $(navbarUserDropdownMobile).addClass('active');
                $('#topbar-user-dropdown-mobile').slideDown(300);
            }
        } else if ($('#topbar-user-dropdown-mobile').is(':visible')) {
            if (targetMatches(false, target, '#topbar-user-dropdown-mobile, #topbar-user-dropdown-mobile *')) {
                $(navbarUserDropdownMobile).removeClass('active');
                $('#topbar-user-dropdown-mobile').slideUp(300);
            }
        }
    });
});

function targetMatches(does, eventTarget, target)
{
    if (does) {
        return (eventTarget.matches) ? eventTarget.matches(target) : eventTarget.msMatchesSelector(target);
    } else {
        return (eventTarget.matches) ? !eventTarget.matches(target) : !eventTarget.msMatchesSelector(target);
    }
}
