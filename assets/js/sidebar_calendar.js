




/*
     FILE ARCHIVED ON 9:06:23 Apr 24, 2013 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 22:37:15 Dec 20, 2014.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
$(document).ready(function () {
  SidebarCalendar.init("#sidebar .calendar", "#sidebar .events");
});

SidebarCalendar = {
  elem: null,
  elem_events: null,

  template: "\
    {{#events}}\
      <div class='event' onclick='window.location = \"{{url_prefix}}{{id}}\"'>\
        <div class='title'>{{title}}</div>\
        <div class='time'>{{time_pretty_start}} - {{time_pretty_end}}</div>\
      </div>\
    {{/events}}\
  ",

  init: function(elem_sel, elem_events_sel) {
    this.elem = $(elem_sel);
    this.elem_events = $(elem_events_sel);

    this.fetch();

    var self = this;
    this.elem.datepicker({
      gotoCurrent: true,
      dateFormat: 'yy-mm-dd',
      onSelect: function(dateText, inst) {
        self.onSelect(dateText);
      }
    });
  },
  onSelect:function(dateText, inst) {
    this.fetch(dateText);
  },
  fetch: function(dateText) {
    if (typeof dateText === "undefined") {
      dateText = (new Date()).toISOString().replace(/T.*Z/, "");
    }
    var self = this;
    $.get(SITE_URL + "/event/get_events_by_date/" + dateText, function(events) {
      self.elem_events.html($.mustache(self.template, {
        events: events,
        url_prefix: SITE_URL + "/event/view/"
      }));
    });
  }
}
