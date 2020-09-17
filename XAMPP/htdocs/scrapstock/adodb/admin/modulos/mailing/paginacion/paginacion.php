<?php

class Zebra_Pagination{
    private $_properties = array(
        'always_show_navigation'    =>  true,
        'avoid_duplicate_content'   =>  true,
        'method'                    =>  'get',
        'next'                      =>  'Siguiente',
        'padding'                   =>  true,
        'page'                      =>  1,
        'page_set'                  =>  false,
        'navigation_position'       =>  'outside',
        'preserve_query_string'     =>  0,
        'previous'                  =>  'Anterior',
        'records'                   =>  '',
        'records_per_page'          =>  '',
        'reverse'                   =>  false,
        'selectable_pages'          =>  12,
        'total_pages'               =>  0,
        'trailing_slash'            =>  true,
        'variable_name'             =>  'Página',
    );

    function __construct(){
        $this->base_url();
    }

    public function always_show_navigation($show = true) {
        $this->_properties['always_show_navigation'] = $show;
    }

    function avoid_duplicate_content($avoid_duplicate_content = true){
        $this->_properties['avoid_duplicate_content'] = $avoid_duplicate_content;
    }

    public function base_url($base_url = '', $preserve_query_string = true){
        $base_url = ($base_url == '' ? $_SERVER['REQUEST_URI'] : $base_url);
        $parsed_url = parse_url($base_url);
        $this->_properties['base_url'] = $parsed_url['path'];
        $this->_properties['base_url_query'] = isset($parsed_url['query']) ? $parsed_url['query'] : '';
        parse_str($this->_properties['base_url_query'], $this->_properties['base_url_query']);
        $this->_properties['preserve_query_string'] = $preserve_query_string;
    }

    public function get_page(){
        if (!$this->_properties['page_set']) {
            if (
                $this->_properties['method'] == 'url' &&
                preg_match('/\b' . preg_quote($this->_properties['variable_name']) . '([0-9]+)\b/i', $_SERVER['REQUEST_URI'], $matches) > 0
            )
                $this->set_page((int)$matches[1]);
            elseif (isset($_GET[$this->_properties['variable_name']]))
                $this->set_page((int)$_GET[$this->_properties['variable_name']]);}

        if ($this->_properties['reverse'] && $this->_properties['records'] == '') trigger_error('When showing records in reverse order you must specify the total number of records (by calling the "records" method) *before* the first use of the "get_page" method!', E_USER_ERROR);

        if ($this->_properties['reverse'] && $this->_properties['records_per_page'] == '') trigger_error('When showing records in reverse order you must specify the number of records per page (by calling the "records_per_page" method) *before* the first use of the "get_page" method!', E_USER_ERROR);

        $this->_properties['total_pages'] = $this->get_pages();

        if ($this->_properties['total_pages'] > 0) {

            if ($this->_properties['page'] > $this->_properties['total_pages']) $this->_properties['page'] = $this->_properties['total_pages'];

            elseif ($this->_properties['page'] < 1) $this->_properties['page'] = 1;
        }

        if (!$this->_properties['page_set'] && $this->_properties['reverse']) $this->set_page($this->_properties['total_pages']);

        return $this->_properties['page'];
    }

    public function get_pages() {
        return @ceil($this->_properties['records'] / $this->_properties['records_per_page']);
    }
 
    public function labels($previous = 'Previous page', $next = 'Next page'){
        $this->_properties['previous'] = $previous;
        $this->_properties['next'] = $next;
    }

    public function method($method = 'get'){
        $this->_properties['method'] = (strtolower($method) == 'url' ? 'url' : 'get') ;
    }

    function navigation_position($position){
        $this->_properties['navigation_position'] = (in_array(strtolower($position), array('left', 'right')) ? strtolower($position) : 'outside') ;
    }

    public function padding($enabled = true){
        $this->_properties['padding'] = $enabled;
    }

    public function records($records){
        $this->_properties['records'] = (int)$records;
    }

    public function records_per_page($records_per_page){
        $this->_properties['records_per_page'] = (int)$records_per_page;
    }

    public function render($return_output = false){
        $this->get_page();
        // if there is a single page, or no pages at all, don't display anything
        if ($this->_properties['total_pages'] <= 1) return '';
        // start building output
        $output = '<nav ><ul class="pagination">';
        // if we're showing records in reverse order
        if ($this->_properties['reverse']) {
            // if "next page" and "previous page" links are to be shown to the left of the links to individual pages
            if ($this->_properties['navigation_position'] == 'left')
                // first show next/previous and then page links
                $output .= $this->_show_next() . $this->_show_previous() . $this->_show_pages();
            // if "next page" and "previous page" links are to be shown to the right of the links to individual pages
            elseif ($this->_properties['navigation_position'] == 'right')
                $output .= $this->_show_pages() . $this->_show_next() . $this->_show_previous();
            // if "next page" and "previous page" links are to be shown on the outside of the links to individual pages
            else $output .= $this->_show_next() . $this->_show_pages() . $this->_show_previous();
        // if we're showing records in natural order
        } else {
            // if "next page" and "previous page" links are to be shown to the left of the links to individual pages
            if ($this->_properties['navigation_position'] == 'left')
                // first show next/previous and then page links
                $output .= $this->_show_previous() . $this->_show_next() . $this->_show_pages();
            // if "next page" and "previous page" links are to be shown to the right of the links to individual pages
            elseif ($this->_properties['navigation_position'] == 'right')
                $output .= $this->_show_pages() . $this->_show_previous() . $this->_show_next();
            // if "next page" and "previous page" links are to be shown on the outside of the links to individual pages
            else $output .= $this->_show_previous() . $this->_show_pages() . $this->_show_next();
        }
        // finish generating the output
        $output .= '</ul></nav>';
        // if $return_output is TRUE
        // return the generated content
        if ($return_output) return $output;
        // if script gets this far, print generated content to the screen
        echo $output;
    }

    public function reverse($reverse = false)
    {
        // set how the pagination links should be generated
        $this->_properties['reverse'] = $reverse;
    }

    public function selectable_pages($selectable_pages)
    {
        // the number of selectable pages
        // make sure we save it as an integer
        $this->_properties['selectable_pages'] = (int)$selectable_pages;
    }

    public function set_page($page)
    {
        // set the current page
        // make sure we save it as an integer
        $this->_properties['page'] = (int)$page;
        // if the number is lower than one
        // make it '1'
        if ($this->_properties['page'] < 1) $this->_properties['page'] = 1;
        // set a flag so that the "get_page" method doesn't change this value
        $this->_properties['page_set'] = true;
    }

    public function trailing_slash($enabled)
    {
        // set the state of trailing slashes
        $this->_properties['trailing_slash'] = $enabled;
    }

    public function variable_name($variable_name)
    {
        // set the variable name
        $this->_properties['variable_name'] = strtolower($variable_name);
    }

    private function _build_uri($page)
    {
        // if page propagation method is through SEO friendly URLs
        if ($this->_properties['method'] == 'url') {
            // see if the current page is already set in the URL
            if (preg_match('/\b' . $this->_properties['variable_name'] . '([0-9]+)\b/i', $this->_properties['base_url'], $matches) > 0) {
                // build string
                $url = str_replace('//', '/', preg_replace(
                    // replace the currently existing value
                    '/\b' . $this->_properties['variable_name'] . '([0-9]+)\b/i',
                    // if on the first page, remove it in order to avoid duplicate content
                    ($page == 1 ? '' : $this->_properties['variable_name'] . $page),
                    $this->_properties['base_url']
                ));
            // if the current page is not yet in the URL, set it, unless we're on the first page
            // case in which we don't set it in order to avoid duplicate content
            } else $url = rtrim($this->_properties['base_url'], '/') . '/' . ($this->_properties['variable_name'] . $page);
            // handle trailing slash according to preferences
            $url = rtrim($url, '/') . ($this->_properties['trailing_slash'] ? '/' : '');
            // if values in the query string - other than those set through base_url() - are not to be preserved
            // preserve only those set initially
            if (!$this->_properties['preserve_query_string']) $query = implode('&', $this->_properties['base_url_query']);
            // otherwise, get the current query string
            else $query = $_SERVER['QUERY_STRING'];
            // return the built string also appending the query string, if any
            return $url . ($query != '' ? '?' . $query : '');
        // if page propagation is to be done through GET
        } else {
            // if values in the query string - other than those set through base_url() - are not to be preserved
            // preserve only those set initially
            if (!$this->_properties['preserve_query_string']) $query = $this->_properties['base_url_query'];
            // otherwise, get the current query string, if any, and transform it to an array
            else parse_str($_SERVER['QUERY_STRING'], $query);
            // if we are avoiding duplicate content and if not the first/last page (depending on whether the pagination links are shown in natural or reversed order)
            if (!$this->_properties['avoid_duplicate_content'] || ($page != ($this->_properties['reverse'] ? $this->_properties['total_pages'] : 1)))
                // add/update the page number
                $query[$this->_properties['variable_name']] = $page;
            // if we are avoiding duplicate content, don't use the "page" variable on the first/last page
            elseif ($this->_properties['avoid_duplicate_content'] && $page == ($this->_properties['reverse'] ? $this->_properties['total_pages'] : 1))
                unset($query[$this->_properties['variable_name']]);
            // make sure the returned HTML is W3C compliant
            return htmlspecialchars(html_entity_decode($this->_properties['base_url']) . (!empty($query) ? '?' . urldecode(http_build_query($query)) : ''));
        }
    }

    private function _show_next()
    {
        $output = '';
        // if "always_show_navigation" is TRUE or
        // if the total number of available pages is greater than the number of pages to be displayed at once
        // it means we can show the "next page" link
        if ($this->_properties['always_show_navigation'] || $this->_properties['total_pages'] > $this->_properties['selectable_pages'])
            $output = '<li><a href="' .
                // the href is different if we're on the last page
                ($this->_properties['page'] == $this->_properties['total_pages'] ? 'javascript:void(0)' : $this->_build_uri($this->_properties['page'] + 1)) .
                // if we're on the last page, the link is disabled
                // also different class if links are in reverse order
                '" class="navigation ' . ($this->_properties['reverse'] ? 'previous' : 'next') . ($this->_properties['page'] == $this->_properties['total_pages'] ? ' disabled' : '') . '"' .
                // good for SEO
                // http://googlewebmastercentral.blogspot.de/2011/09/pagination-with-relnext-and-relprev.html
                ' rel="next"' .
                '>' . $this->_properties['next'] . '</a></li>';
        // return the resulting string
        return $output;
    }
 
    private function _show_pages()
    {
        $output = '';
        // if the total number of pages is lesser than the number of selectable pages
        if ($this->_properties['total_pages'] <= $this->_properties['selectable_pages']) {
            // iterate ascendingly or descendingly depending on whether we're showing links in reverse order or not)
            for (
                $i = ($this->_properties['reverse'] ? $this->_properties['total_pages'] : 1);
                ($this->_properties['reverse'] ? $i >= 1 : $i <= $this->_properties['total_pages']);
                ($this->_properties['reverse'] ? $i-- : $i++)
            )
                // render the link for each page
                $output .= '<li><a href="' . $this->_build_uri($i) . '" ' .
                    // make sure to highlight the currently selected page
                    ($this->_properties['page'] == $i ? 'class="current"' : '') . '>' .
                    // apply padding if required
                    ($this->_properties['padding'] ? str_pad($i, strlen($this->_properties['total_pages']), '0', STR_PAD_LEFT) : $i) .
                    '</a></li>';
        // if the total number of pages is greater than the number of selectable pages
        } else {
            // start with a link to the first or last page, depending if we're displaying links in reverse order or not
            $output .= '<li><a href="' . $this->_build_uri($this->_properties['reverse'] ? $this->_properties['total_pages'] : 1) . '" ' .
                // highlight if the page is currently selected
                ($this->_properties['page'] == ($this->_properties['reverse'] ? $this->_properties['total_pages'] : 1) ? 'class="current"' : '') . '>' .
                // if padding is required
                ($this->_properties['padding'] ?
                    // apply padding
                    str_pad(($this->_properties['reverse'] ? $this->_properties['total_pages'] : 1), strlen($this->_properties['total_pages']), '0', STR_PAD_LEFT) :
                    // show the page number
                    ($this->_properties['reverse'] ? $this->_properties['total_pages'] : 1)) .
                '</a></li>';
            // compute the number of adjacent pages to display to the left and right of the currently selected page so
            // that the currently selected page is always centered
            $adjacent = floor(($this->_properties['selectable_pages'] - 3) / 2);
            // this number must be at least 1
            if ($adjacent == 0) $adjacent = 1;
            // find the page number after we need to show the first "..."
            // (depending on whether we're showing links in reverse order or not)
            $scroll_from = ($this->_properties['reverse'] ?
                $this->_properties['total_pages'] - ($this->_properties['selectable_pages'] - $adjacent) + 1 :
                $this->_properties['selectable_pages'] - $adjacent);
            // get the page number from where we should start rendering
            // if displaying links in natural order, then it's "2" because we have already rendered the first page
            // if we're displaying links in reverse order, then it's total_pages - 1 because we have already rendered the last page
            $starting_page = ($this->_properties['reverse'] ? $this->_properties['total_pages'] - 1 : 2);
            // if the currently selected page is past the point from where we need to scroll,
            // we need to adjust the value of $starting_page
            if (
                ($this->_properties['reverse'] && $this->_properties['page'] <= $scroll_from) ||
                (!$this->_properties['reverse'] && $this->_properties['page'] >= $scroll_from)
            ) {
                // by default, the starting_page should be whatever the current page plus/minus $adjacent
                // depending on whether we're showing links in reverse order or not
                $starting_page = $this->_properties['page'] + ($this->_properties['reverse'] ? $adjacent : -$adjacent);
                // but if that would mean displaying less navigation links than specified in $this->_properties['selectable_pages']
                if (
                    ($this->_properties['reverse'] && $starting_page < ($this->_properties['selectable_pages'] - 1)) ||
                    (!$this->_properties['reverse'] && $this->_properties['total_pages'] - $starting_page < ($this->_properties['selectable_pages'] - 2))
                )
                    // adjust the value of $starting_page again
                    if ($this->_properties['reverse']) $starting_page = $this->_properties['selectable_pages'] - 1;
                    else $starting_page -= ($this->_properties['selectable_pages'] - 2) - ($this->_properties['total_pages'] - $starting_page);
                // put the "..." after the link to the first/last page
                // depending on whether we're showing links in reverse order or not
                $output .= '<li><span>&hellip;</span></li>';
            }
            // get the page number where we should stop rendering
            // by default, this value is the sum of the starting page plus/minus (depending on whether we're showing links
            // in reverse order or not) whatever the number of $this->_properties['selectable_pages'] minus 3 (first page,
            // last page and current page)
            $ending_page = $starting_page + (($this->_properties['reverse'] ? -1 : 1) * ($this->_properties['selectable_pages'] - 3));
            // if we're showing links in natural order and ending page would be greater than the total number of pages minus 1
            // (minus one because we don't take into account the very last page which we output automatically)
            // adjust the ending page
            if ($this->_properties['reverse'] && $ending_page < 2) $ending_page = 2;
            // or, if we're showing links in reverse order, and ending page would be smaller than 2
            // (2 because we don't take into account the very first page which we output automatically)
            // adjust the ending page
            elseif (!$this->_properties['reverse'] && $ending_page > $this->_properties['total_pages'] - 1) $ending_page = $this->_properties['total_pages'] - 1;
            // render pagination links
            for ($i = $starting_page; $this->_properties['reverse'] ? $i >= $ending_page : $i <= $ending_page; $this->_properties['reverse'] ? $i-- : $i++)
                $output .= '<li><a href="' . $this->_build_uri($i) . '" ' .
                    // highlight the currently selected page
                    ($this->_properties['page'] == $i ? 'class="current"' : '') . '>' .
                    // apply padding if required
                    ($this->_properties['padding'] ? str_pad($i, strlen($this->_properties['total_pages']), '0', STR_PAD_LEFT) : $i) .
                    '</a></li>';
            // if we have to, place another "..." at the end, before the link to the last/first page (depending on whether
            // we're showing links in reverse order or not)
            if (
                ($this->_properties['reverse'] && $ending_page > 2) ||
                (!$this->_properties['reverse'] && $this->_properties['total_pages'] - $ending_page > 1)
            ) $output .= '<li><span>&hellip;</span></li>';
            // put a link to the last/first page (depending on whether we're showing links in reverse order or not)
            $output .= '<li><a href="' . $this->_build_uri($this->_properties['reverse'] ? 1 : $this->_properties['total_pages']) . '" ' .
                // highlight if it is the currently selected page
                ($this->_properties['page'] == $i ? 'class="current"' : '') . '>' .
                // also, apply padding if necessary
                ($this->_properties['padding'] ? str_pad(($this->_properties['reverse'] ? 1 : $this->_properties['total_pages']), strlen($this->_properties['total_pages']), '0', STR_PAD_LEFT) : ($this->_properties['reverse'] ? 1 : $this->_properties['total_pages'])) .
                '</a></li>';
        }
        // return the resulting string
        return $output;
    }

    private function _show_previous()
    {
        $output = '';
        // if "always_show_navigation" is TRUE or
        // if the number of total pages available is greater than the number of selectable pages
        // it means we can show the "previous page" link
        if ($this->_properties['always_show_navigation'] || $this->_properties['total_pages'] > $this->_properties['selectable_pages'])
            $output = '<li><a href="' .
                // the href is different if we're on the first page
                ($this->_properties['page'] == 1 ? 'javascript:void(0)' : $this->_build_uri($this->_properties['page'] - 1)) .
                // if we're on the first page, the link is disabled
                // also different class if links are in reverse order
                '" class="navigation ' . ($this->_properties['reverse'] ? 'next' : 'previous') . ($this->_properties['page'] == 1 ? ' disabled' : '') . '"' .
                // good for SEO
                // http://googlewebmastercentral.blogspot.de/2011/09/pagination-with-relnext-and-relprev.html
                ' rel="prev"' .
                '>' . $this->_properties['previous'] . '</a></li>';
        // return the resulting string
        return $output;
    }
}
?>