<?php

namespace Core\Support;

class Paginator
{
    /**
     * @param $links
     * @return string
     */
    public static  function pagination($links): string
    {
        $links = (object)$links;
        $html = '';
        // Info line with safe defaults
        $from = $links->from ?? 0;
        $to = $links->to ?? 0;
        $total = $links->total ?? 0;
        if ($total > 0) {
            $html .= '<div class="pagination-info">Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries</div>';
        }
        if (isset($links->last_page) && $links->last_page > 1) {
            $html .= '<ul class="pagination">';
            // Previous link (always shown, disabled if on first page)
            if ($links->current_page <= 1) {
                $html .= '<li class="disabled"><span>&laquo; Previous</span></li>';
            } else {
                $html .= '<li><a href="' . $links->prev_page_url . '">&laquo; Previous</a></li>';
            }
            $window = 2; // Number of pages to show before/after current
            $start = max(1, $links->current_page - $window);
            $end = min($links->last_page, $links->current_page + $window);

            // Always show first page
            if ($start > 1) {
                $html .= '<li><a href="' . $links->path . '?page=1">1</a></li>';
                if ($start > 2) {
                    $html .= '<li class="disabled"><span>...</span></li>';
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                $active = $i == $links->current_page ? ' class="active"' : '';
                $html .= '<li' . $active . '><a href="' . $links->path . '?page=' . $i . '">' . $i . '</a></li>';
            }

            // Always show last page
            if ($end < $links->last_page) {
                if ($end < $links->last_page - 1) {
                    $html .= '<li class="disabled"><span>...</span></li>';
                }
                $html .= '<li><a href="' . $links->path . '?page=' . $links->last_page . '">' . $links->last_page . '</a></li>';
            }

            // Next link (always shown, disabled if on last page)
            if ($links->current_page >= $links->last_page) {
                $html .= '<li class="disabled"><span>Next &raquo;</span></li>';
            } else {
                $html .= '<li><a href="' . $links->next_page_url . '">Next &raquo;</a></li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    /**
     * @param $links
     * @return string
     */
    public static  function simplePagination($links)
    {

        $html = '';
        $show = self::showPages($links);

        if ($show != 0) {
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = 1;
            }

            if ($page == 1) {
                $prev = '<span class="page-link">&laquo; Previous</span>';
                $disabled = 'disabled';
            } else {
                $prev = ' <a href="' . $links->prev_page_url . '">Previous</a>';
                $disabled = '';
            }

            $html = '<ul class="pagination" role="navigation">
        <li class="page-item ' . $disabled . '" aria-disabled="true">
        ' . $prev . '
        </li>';

            if ($page < $show) {
                $nexLink = $links->next_page_url;
                $disabled = '';
            } else {
                $disabled = 'disabled';
                $nexLink = 'javascript:void(0);';

            }

            $html .= '<li class="page-item ' . $disabled . '">
            <a class="page-link" href="' . $nexLink . '" rel="next">Next &raquo;</a></li>';

            $html .= '</ul>';
        }

        return $html;
    }

    /**
     * @param $links
     * @return float|int
     */
    private static  function showPages($links): float|int
    {
        $show = 0;
        if ($links->total > $links->per_page) {

            $show = ($links->total / $links->per_page);
        }
        return $show;
    }
}

