<?php

namespace Core\Support;

class Paginator
{
    /**
     * @param $links
     * @return string
     */
    public static function pagination($links): string
    {
        $links = (object) $links;
        $html = '';

        $from = $links->from ?? 0;
        $to = $links->to ?? 0;
        $total = $links->total ?? 0;

        if ($total > 0) {
            $html .= '<div class="d-flex pagination-info mb-2 justify-content-end mt-3">Showing ' . $from . ' to ' . $to . ' of ' . $total . ' entries</div>';
        }

        if (isset($links->last_page) && $links->last_page > 1) {
            $html .= '<div class="d-flex justify-content-end mt-3"><nav><ul class="pagination">';

            // Previous
            if ($links->current_page <= 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">&laquo; Previous</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . $links->prev_page_url . '">&laquo; Previous</a></li>';
            }

            $window = 2;
            $start = max(1, $links->current_page - $window);
            $end = min($links->last_page, $links->current_page + $window);

            // First page
            if ($start > 1) {
                $html .= '<li class="page-item"><a class="page-link" href="' . $links->path . '?page=1">1</a></li>';
                if ($start > 2) {
                    $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                $active = $i == $links->current_page ? ' active' : '';
                $html .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $links->path . '?page=' . $i . '">' . $i . '</a></li>';
            }

            // Last page
            if ($end < $links->last_page) {
                if ($end < $links->last_page - 1) {
                    $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                $html .= '<li class="page-item"><a class="page-link" href="' . $links->path . '?page=' . $links->last_page . '">' . $links->last_page . '</a></li>';
            }

            // Next
            if ($links->current_page >= $links->last_page) {
                $html .= '<li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . $links->next_page_url . '">Next &raquo;</a></li>';
            }

            $html .= '</ul></nav></div>';
        }

        return $html;
    }


    /**
     * @param $links
     * @return string
     */
    public static function simplePagination($links)
    {
        $html = '';
        $show = self::showPages($links);

        if ($show != 0) {
            $page = $_GET['page'] ?? 1;

            // Previous button
            if ($page == 1) {
                $html .= '<ul class="pagination d-flex justify-content-end mt-3">';
                $html .= '<li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">&laquo; Previous</span>
                      </li>';
            } else {
                $html .= '<ul class="pagination d-flex justify-content-end mt-3">';
                $html .= '<li class="page-item">
                        <a class="page-link" href="' . $links->prev_page_url . '" rel="prev">&laquo; Previous</a>
                      </li>';
            }

            // Next button
            if ($page < $show) {
                $html .= '<li class="page-item">
                        <a class="page-link" href="' . $links->next_page_url . '" rel="next">Next &raquo;</a>
                      </li>';
            } else {
                $html .= '<li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Next &raquo;</span>
                      </li>';
            }

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

