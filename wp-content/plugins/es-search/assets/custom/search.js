c('v: 1.0.2');

var aggs = [ '-' ];

(function ($) {
  let siteId = $('#filter_siteId').val();

  let sep = $('#filter_separator').val();
  aggs_default();
  search(1);

  $('#filter_button').on( 'click', function () {
    search(1);
  });

  $('#filter_query').on('keypress',function(e) {
    if(e.which == 13) {
      e.preventDefault();
      search(1);
    }
  });

  // desktop
  $('.filter_contentType').on( 'click', function () {
    contentType__click($(this).attr('data-value'))
    search(1, { 'pagination': '0', 'type': '1' });
  });

  // mobile
  $('#filter_contentType').on( 'change', function () {
    contentType__click($(this).val())
    search(1, { 'pagination': '0', 'type': '1' });
  });

  $('#searchAjax__paginate').on( 'click', 'a', function (e) {
    let page = parseInt($(this).attr('data-page'));
    search(page,{ 'pagination': '1', 'type': '0' });

    e.preventDefault();
  });

  $('.filter_button_reset').on( 'click', function (e) {
    let url = location.href;
    let list = url.split('?');
    if (list.length > 0) {
      location.href = list[0] + '?s=';
    }
    else {
      location.href = url;
    }
  });

  function aggs_default() {
    let data = $('#filter_aggs').text();
    aggs = JSON.parse(data);
  }

  function search(page, other = { 'pagination': '0', 'type': '1' }) {
    let div = $('#searchAjax');
    let form = {
      'site': siteId,
      'page': page,
      'size': 6,
      'print': 'json_type',
      'query': $('#filter_query').val(),
      'content_type': $('#filter_contentType').val(),
      'or_group': aggs,
      'pagination': other['pagination']
    };


    $('#searchAjax__body').html(load);
    $('.searchAjax__otherBtns').addClass('d-none');

    $('#searchAjax__paginate').addClass('d-none');

    if (other['pagination'] == 0) {
      $('.searchAjax__aggs').html(load);
      $('.select2-container').remove();
    }

    $.ajax({
      type: 'GET',
      data: form,
      url: div.attr('data-url'),
      success: function (response) {
        if (response['status'] == 'ok')
          search_ajax(response, form, div.attr('data-url-home'), other);
        else
          a(response['msg']);

        $('.searchAjax__otherBtns').removeClass('d-none');
        $('#searchAjax__paginate').removeClass('d-none');
      }
    });
  }

  function search_ajax(response, form, url_home, other) {
    search_ajax_count(response, form)
    search_ajax_body(response, form, url_home)
    search_ajax_paginate(response, form)

    if (other['pagination'] == 0) {
      search_ajax_agg(response);

      if (other['type'] == 0) {
        search_ajax_agg_type(response['aggs']['type_agg']['data']);
      }
    }
  }

  function search_ajax_paginate(response, form) {
    let div = $('#searchAjax__paginate');
    let html = '';
    let len = 3, p;

    if (form['page'] == 1) {
      div.attr('data-count', response['data']['hits']['total']['value']);
    }
    let pages = parseInt(div.attr('data-count')) / form['size'];
    pages = Math.ceil(pages);

    if (form['page'] > 1) {
      p = 1;
      html += '<a href="#" class="p-3 text-secondary" data-page="' + p + '">&laquo;</a>';
      p = form['page'] - 1;
      html += '<a href="#" class="pr-3 text-secondary" data-page="' + p + '">&larr;</a>';
    }
    for (let i = 0; i < len; i++) {
      p = form['page'] + i;
      if (p > pages) {
        break;
      }
      if (i == 0) {
        html += '<ins href="#" class="px-3 text-secondary">' + p + '</ins>';
        continue
      }
      html += '<a href="#" class="px-3 text-secondary" data-page="' + p + '">' + p + '</a>';
    }
    if (p < pages) {
      p = form['page'] + 1;
      html += '<a href="#" class="p-3 text-secondary" data-page="' + p + '">&rarr;</a>';
      p = pages;
      html += '<a href="#" class="pl-3 text-secondary" data-page="' + p + '">&raquo;</a>';
    }

    div.html(html);
  }

  function search_ajax_count(response, form) {
    let div_count = $('#searchAjax__count');
    let html, from, last;

    div_count.hide();
    if (response['data']['hits']['hits'].length < 1) {
      return;
    }

    from = ((form['page'] - 1) * form['size']) + 1 ;
    last = from + response['data']['hits']['hits'].length - 1;

    if (form['page'] == 1) {
      div_count.children('._count').html(response['data']['hits']['total']['value']);
    }
    div_count.children('._from').html(from);
    div_count.children('._last').html(last);

    div_count.show();
  }

  function search_ajax_body(response, form, url_home) {
    let div = $('#searchAjax__body');
    let html = '', json;

    $.each( response['data']['hits']['hits'], function( i, row ) {
      json = JSON.parse(row['fields']['json_type'][0]);
      json['type'] = row['fields']['type'][0];
      html += search_ajax_body_row(json, url_home);
    });
    div.html(html);
  }

  function search_ajax_body_row(json, url_home) {
    let tmp = '', _tmp, url, date;

    url =  url_home + json['type'] +  '?nid=' + json['id'];
    if (json['url'] != '') {
      url = json['url'];
    }

    tmp += '<div class="card mb-3 border-0">';
    tmp += '  <div class="row m-0">';

    tmp += '    <div class=" col-lg-2 col-md-2 col-3 p-0 ">';
    if (json['image'] != '') { tmp += '      <img src="' + json['image'] + '" class="w-100">';}
    tmp += '    </div>';

    tmp += '    <div class="col-lg-10 col-md-10 col-9">';
    tmp += '      <div class="card-body p-0">';
    if (json['tipo'] != '' || json['numero'] != '') {
      _tmp = '';
      if (json['tipo'] != '') { _tmp += json['tipo'] + ' ';}
      if (json['numero'] != '') { _tmp += 'N° ' + json['numero'];}
      tmp += '        <p class="mb-1"><strong>' + _tmp + '</strong></p>';
    }
    if (json['title'] != '') { tmp += '        <h5 class="card-title"><a href="' + url + '" target="_blank">' + json['title'] + '</a></h5>';}

    tmp += '        <p class="card-text"><small>';
    if (json['creation_date'] != '') {
      date = new Date(json['creation_date']);
      tmp +=  date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear() + '<br>';
    }

    if (json['fuente'] != '' && Array.isArray(json['fuente'])) {
      $.each( json['fuente'], function( i, row ) {
        tmp += row + '<br>';
      });
    }

    tmp += '        </small></p>';
    tmp += '        <p class="card-text">';
    tmp += '          <a href="' + url + '" class="btn btn-sm btn-success text-white px-4" target="_blank"><small>Ver Más</small></a>';
    tmp += '        </p>';
    tmp += '';
    tmp += '      </div>';
    tmp += '    </div>';
    tmp += '  </div>';
    tmp += '</div><hr>';

    return tmp
  }

  function search_ajax_agg(response) {
    let div = $('.searchAjax__aggs');
    let html = '';

    div.html(load);

    $.each( response['aggs'], function( i, row ) {
      html += search_ajax_agg_row(row);
    });

    div.html(html);

    $('.searchAjax__aggs .js-agg-multiple')
      .select2({
        width: 'resolve',
        theme: "classic",
        placeholder: "Seleccione"
      })
      .on('.searchAjax__aggs select2:select', function (e) {
        let data = e.params.data;
        search_ajax_agg_captureAgg(data['id'], 'select');
      })
      .on('.searchAjax__aggs select2:unselect', function (e) {
        let data = e.params.data;
        search_ajax_agg_captureAgg(data['id'], 'unselect');
      });

    $('#filter_contentType').select2({
      placeholder: "Seleccione",
    });
  }

  function search_ajax_agg_type(data) {
    $('.filter_contentType').attr('disabled','disabled');
    $('#filter_contentType option').attr('disabled','disabled');

    $('#filter_contentType_all').removeAttr('disabled');
    $('#filter_contentType_mobile_all').removeAttr('disabled');

    $.each( data, function( i, row ) {
      $('#filter_contentType_' + row).removeAttr('disabled');
      $('#filter_contentType_mobile_' + row).removeAttr('disabled');
    });
  }

  function search_ajax_agg_row(row) {
    if (row['name'] == 0) {
      return '';
    }
    let o = '', s = '', selected = '', value;

    $.each( row['data'], function( i, row_data ) {
      s += '<option value="' + row['name'] + sep + row_data['value'] + '" ' + row_data['selected'] + '>';
      s += row_data['label'] + '(' + row_data['count'] + ')' + '</option>'
    });

    if (s == '') {
      return '';
    }

    o += '<div class="mb-3">';
    o += '<label class="d-block">' + row['label'];
    o += '<select class="js-agg-multiple"  multiple="multiple" style="width: 100%" ' + selected + '>';
    o += s;
    o += '</select></label>';
    o += row['before'];
    o += '</div>';

    return o;
  }

  function search_ajax_agg_captureAgg(value, event) {
    let list = $('.filter_contentType');

    if (event == 'select') {
      aggs.push(value);
    }
    if (event == 'unselect') {
      for (var i = aggs.length - 1; i >= 0; i--) {
        if (aggs[i] === value) {
          aggs.splice(i, 1);
        }
      }
    }

    list.removeClass('btn-primary');
    list.addClass('btn-outline-primary');

    $('#filter_contentType_all')
      .addClass('btn-primary')
      .removeClass('btn-outline-primary')
    ;


    $('#filter_contentType').val('all');

    search(1, { 'pagination': '0', 'type': '0' });
  }

  function contentType__click(value) {
    let list = $('.filter_contentType');
    let item = $('#filter_contentType_' + value);

    if (item.hasClass('btn-primary')) {
      // item.addClass('btn-outline-primary');
      // item.removeClass('btn-primary');
      // $('#filter_contentType').val('all')
    }
    else {
      list.removeClass('btn-primary');
      list.addClass('btn-outline-primary');
      item.removeClass('btn-outline-primary');
      item.addClass('btn-primary');
      $('#filter_contentType').val(item.attr('data-value'))
    }
  }

})(jQuery);

var load =
  '<div class="text-center">' +
  '  <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">' +
  '    <span class="visually-hidden">Loading...</span>' +
  '  </div>' +
  '</div>';
