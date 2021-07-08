<?php

use function Dev4Press\v35\Functions\panel;

$_subpanel  = panel()->a()->subpanel;
$_subpanels = panel()->subpanels();

?>
<div class="d4p-sidebar">
	<?php if ( demopress_admin()->subpanel == 'index' && demopress_gen()->is_idle() ) { ?>
        <div class="d4p-dashboard-badge" style="background-color: <?php echo panel()->a()->settings()->i()->color(); ?>;">
            <div class="d4p-dashboard-sidebard-svg-sign">
                <img alt="DemoPress Spider Sign" src='data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDM2MCAzNjAiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM6c2VyaWY9Imh0dHA6Ly93d3cuc2VyaWYuY29tLyIgc3R5bGU9ImZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoyOyI+CiAgICA8ZyB0cmFuc2Zvcm09Im1hdHJpeCgwLjI3MTQzLDAsMCwwLjI3MTQzLC0xNS4wNjg0LC0xLjAwNDU5KSI+CiAgICAgICAgPHBhdGggZD0iTTU2OS41MTEsNTEyLjExNUM1NDEuMjEsNDM0Ljk0NiA1MDYuODk5LDMzNC45MTMgNDk5LjQyNSwzMTIuNzMxTDQ5OS4yNzUsMzEyLjI0OUw0NzguMDgyLDI4OC4zODRDNDY3LjQ4OSwyNzYuNDkyIDQ1Ni44OTYsMjY0LjYwMSA0NDYuMzAzLDI1Mi43MDlMNDA2LjkzLDIwOC41NTlMMjg2LjUyLDEzMy42MzlDMjc2LjUyMywxMjcuNDE5IDI3My40NTcsMTE0LjI1MiAyNzkuNjc3LDEwNC4yNTVDMjg1Ljg5Nyw5NC4yNTggMjk5LjA2NCw5MS4xOTIgMzA5LjA2MSw5Ny40MTJMNDMyLjA3OCwxNzMuOTU0QzQzMy44MDYsMTc1LjAyOSA0MzUuMzcxLDE3Ni4zNDYgNDM2LjcyNiwxNzcuODY0QzQzNi43MjYsMTc3Ljg2NCA1MTUuNzY1LDI2Ni40NDcgNTMxLjQ3LDI4NC4yNDdDNTM1LjUzNSwyODguODU0IDUzNy4zMTYsMjkzLjU1IDUzNy43NzYsMjk3LjkyTDUzOC41NzcsMjk1LjI2N0M1MzguNTc3LDI5NS4yNjcgNTM5LjE1NywyOTcuMDI5IDUzOS44NTgsMjk5LjEwN0M1NDYuNDEzLDMxOC41NjEgNTczLjc0OSwzOTguMzA4IDU5OS40MTMsNDY5LjQ4OUM2MDUuNDYsNDYzLjA2NCA2MTEuOTU0LDQ1Ny4xMiA2MTguODM3LDQ1MS43MThDNjEyLjE1MSw0MzguNzg5IDU5NC40MDUsNDA0LjIyNyA1ODYuMzUsMzg2LjE1M0M1ODIuMTU5LDM3Ni43NDggNTgwLjY1OCwzNjkuNjM1IDU4MC42NTgsMzY3LjQ5NkM1ODAuNjU4LDM2NS43NCA1ODIuNTcxLDM1Ny4wNTcgNTg3LjA1NCwzNDQuNzE4QzU5OS45OTUsMzA5LjEwMSA2MzIuMzQ0LDIyOC4xNjQgNjMyLjM0NCwyMjguMTY0QzYzNi4zMTIsMjE4LjIzOCA2NDcuNTkyLDIxMy40IDY1Ny41MTksMjE3LjM2OEM2NjcuNDQ1LDIyMS4zMzYgNjcyLjI4MiwyMzIuNjE2IDY2OC4zMTUsMjQyLjU0M0M2NjguMzE1LDI0Mi41NDMgNjM2LjI3NywzMjIuNjggNjIzLjQ2MywzNTcuOTQ3QzYyMi4yODMsMzYxLjE5MyA2MjEuMTI2LDM2NC41OTcgNjIwLjI5NSwzNjcuMDk3QzYyNi4zODIsMzgxLjcxNCA2NDQuMDUzLDQxNi4xODUgNjUxLjkzMiw0MzEuNDA3QzY1Ny45MDksNDI4LjY0MSA2NjQuMDY3LDQyNi4yMzIgNjcwLjM3Nyw0MjQuMjA2QzY1Ny41NjQsNDIxLjg5NyA2NDUuMjY2LDQxNi40ODQgNjQxLjU0MSw0MDguNDcyQzYyOS4yMjEsMzgxLjk2OCA2NTguMDUyLDMyOS4zNjQgNjg4LjgyMSwzMjguMzU5QzY5Ny44NDMsMzI4LjA2NCA3MDUuNDYsMzM3LjQyOSA3MDcuMDE4LDM0NC42NUM3MDkuOTE1LDM1OC4wNzYgNzEzLjEwNyw0MDEuMDc5IDcwNS40NTksNDE3LjE2M0M3MDkuOTcxLDQxNi43NzcgNzE0LjUyOSw0MTYuNTggNzE5LjEzLDQxNi41OEM3MjMuMzkyLDQxNi41OCA3MjcuNjE3LDQxNi43NDkgNzMxLjc5Nyw0MTcuMDhDNzI0LjE5OSw0MDAuOTM1IDcyNy4zODUsMzU4LjA1NCA3MzAuMjc3LDM0NC42NUM3MzEuODM1LDMzNy40MjkgNzM5LjQ1MiwzMjguMDY0IDc0OC40NzQsMzI4LjM1OUM3NzkuMjQzLDMyOS4zNjQgODA4LjA3NCwzODEuOTY4IDc5NS43NTQsNDA4LjQ3MkM3OTIuMDksNDE2LjM1NSA3ODAuMTI0LDQyMS43MjIgNzY3LjUzLDQyNC4wOTNDNzczLjY1Myw0MjYuMDQ0IDc3OS42MzIsNDI4LjM1NiA3ODUuNDQ4LDQzMS4wMDVDNzkzLjQyMiw0MTUuNTk0IDgxMC44NDMsMzgxLjU4OSA4MTYuODc3LDM2Ny4wOTdDODE2LjA0NiwzNjQuNTk3IDgxNC44ODksMzYxLjE5MyA4MTMuNzA5LDM1Ny45NDdDODAwLjg5NSwzMjIuNjggNzY4Ljg1NywyNDIuNTQzIDc2OC44NTcsMjQyLjU0M0M3NjQuODksMjMyLjYxNiA3NjkuNzI3LDIyMS4zMzYgNzc5LjY1MywyMTcuMzY4Qzc4OS41OCwyMTMuNCA4MDAuODYsMjE4LjIzOCA4MDQuODI4LDIyOC4xNjRDODA0LjgyOCwyMjguMTY0IDgzNy4xNzcsMzA5LjEwMSA4NTAuMTE4LDM0NC43MThDODU0LjYwMSwzNTcuMDU3IDg1Ni41MTQsMzY1Ljc0IDg1Ni41MTQsMzY3LjQ5NkM4NTYuNTE0LDM2OS42MzUgODU1LjAxMywzNzYuNzQ4IDg1MC44MjIsMzg2LjE1M0M4NDIuODksNDAzLjk1MiA4MjUuNTU5LDQzNy43NDMgODE4LjY0Niw0NTEuMTE2QzgyNy4yMjEsNDU3Ljc2NyA4MzUuMTk5LDQ2NS4yNTUgODQyLjQ4LDQ3My40NzFDODY4LjU2OCw0MDEuMzA1IDg5Ni44MzUsMzE4Ljg5IDkwMy41MTgsMjk5LjA2OEw5MDMuODI4LDI5OC4xMzlDOTA0LjM0MywyOTUuMDA0IDkwNS43NDcsMjkxLjY0NyA5MDguNDc2LDI4OC4xODFDOTA4LjY4NSwyODcuOTE2IDkwOS44NzcsMjg2LjUxMSA5MTEuOTA4LDI4NC4yMDlDOTI3LjYxNSwyNjYuNDEyIDEwMDYuNjcsMTc3Ljg0MiAxMDA2LjY3LDE3Ny44NDJDMTAwOC4wMiwxNzYuMzI0IDEwMDkuNTksMTc1LjAwOCAxMDExLjMyLDE3My45MzNMMTEzNC4zNSw5Ny40MUMxMTQ0LjM1LDkxLjE5MiAxMTU3LjUxLDk0LjI2IDExNjMuNzMsMTA0LjI1OEMxMTY5Ljk1LDExNC4yNTYgMTE2Ni44OCwxMjcuNDIyIDExNTYuODgsMTMzLjY0MUwxMDM2LjQ2LDIwOC41NDFMOTk3LjA4LDI1Mi42ODVDOTg2LjQ4NSwyNjQuNTc1IDk3NS44OSwyNzYuNDY1IDk2NS4yOTUsMjg4LjM1NUw5NDQuMDk5LDMxMi4yMTdMOTQzLjk0OSwzMTIuNjk5QzkzNi4yNzYsMzM1LjQ1NiA5MDAuMzM5LDQ0MC4xNjYgODcxLjYzNCw1MTguMDQ0Qzg3NS44MjEsNTI3LjA0IDg3OS4zNDIsNTM2LjQ1NSA4ODIuMTI1LDU0Ni4yMTZDOTM5LjU5NSw0OTguMDA1IDEwMDIuMTUsNDQzLjg2MSAxMDE3LjQzLDQzMC40ODlMMTAxOC4xNyw0MjkuODQyQzEwMjAuMTksNDI3LjM5MiAxMDIzLjA5LDQyNS4xOTYgMTAyNy4xOSw0MjMuNTczQzEwMjcuNTEsNDIzLjQ0OCAxMDI5LjI0LDQyMi44MzQgMTAzMi4xNiw0MjEuODY2QzEwNTQuNjgsNDE0LjM4MiAxMTY3LjU2LDM3Ny41ODQgMTE2Ny41NiwzNzcuNTg0QzExNjkuNDksMzc2Ljk1MyAxMTcxLjUxLDM3Ni42MDIgMTE3My41NCwzNzYuNTQyTDEzMTguMzYsMzcyLjI3MkMxMzMwLjEzLDM3MS45MjUgMTMzOS45NywzODEuMTk4IDEzNDAuMzIsMzkyLjk2N0MxMzQwLjY2LDQwNC43MzYgMTMzMS4zOSw0MTQuNTczIDEzMTkuNjIsNDE0LjkyTDExNzcuODcsNDE5LjFMMTEyMS42Myw0MzcuNDUxQzExMDYuNDksNDQyLjQgMTA5MS4zNSw0NDcuMzQ5IDEwNzYuMjIsNDUyLjI5N0wxMDQ1LjksNDYyLjI2MkwxMDQ1LjUyLDQ2Mi42MDRDMTAyOC4zNiw0NzcuNjE5IDk1MS45OTYsNTQzLjY5MyA4ODkuNTQyLDU5NS42MUM4ODkuNTc1LDU5Ny4wNzkgODg5LjU5MSw1OTguNTU4IDg4OS41OTEsNjAwLjA0MUM4ODkuNTkxLDYxMy4xODIgODg4LjMwNSw2MjYuMDAyIDg4NS44NjEsNjM4LjM1NkM5NjYuNzEyLDYzOC45OTcgMTA2Ny44NSw2MzcuNzY3IDEwOTAuNjEsNjM3LjM2OUwxMDkxLjU5LDYzNy4zNDlDMTA5NC43Miw2MzYuNzggMTA5OC4zNiw2MzYuOTc0IDExMDIuNTQsNjM4LjM4NUMxMTAyLjg2LDYzOC40OTMgMTEwNC41OSw2MzkuMTQ1IDExMDcuNDQsNjQwLjI4OEMxMTI5LjQ4LDY0OS4xMjEgMTIzOS41LDY5My45MTcgMTIzOS41LDY5My45MTdDMTI0MS4zOCw2OTQuNjg1IDEyNDMuMTUsNjk1LjcxOCAxMjQ0Ljc0LDY5Ni45ODZMMTM1OC4yNCw3ODcuMzQ1QzEzNjcuNDUsNzk0LjY3OCAxMzY4Ljk3LDgwOC4xMTEgMTM2MS42NCw4MTcuMzIyQzEzNTQuMzEsODI2LjUzMyAxMzQwLjg3LDgyOC4wNTggMTMzMS42Niw4MjAuNzI0TDEyMjAuNTcsNzMyLjI3OEwxMTY1Ljc0LDcwOS45N0MxMTUwLjk4LDcwMy45NzMgMTEzNi4yMiw2OTcuOTc2IDExMjEuNDUsNjkxLjk3OUwxMDkxLjg1LDY4MC4wMDlMMTA5MS4zNiw2ODAuMDI5QzEwNjcuMiw2ODAuNDUyIDk1NS4zMjIsNjgxLjggODcyLjE2Miw2ODAuODk1Qzg2NS43NjgsNjk0Ljg3MiA4NTcuNzcsNzA3Ljg0NyA4NDguNDI5LDcxOS41NDNDOTEwLjk4OSw3NzMuOTYgOTk4LjQwMSw4NDYuNDM2IDEwMTcuMTgsODYxLjgzN0wxMDE3Ljk0LDg2Mi40NTZDMTAyMC42OSw4NjQuMDQ3IDEwMjMuMzQsODY2LjU0NyAxMDI1LjYxLDg3MC4zMjdDMTAyNS43OSw4NzAuNjE2IDEwMjYuNjgsODcyLjIyOCAxMDI4LjExLDg3NC45NDJDMTAzOS4yLDg5NS45MyAxMDk0LjA3LDEwMDEuMjEgMTA5NC4wNywxMDAxLjIxQzEwOTUuMDEsMTAwMy4wMSAxMDk1LjY5LDEwMDQuOTQgMTA5Ni4wOCwxMDA2Ljk0TDExMjQuMTIsMTE0OS4wOUMxMTI2LjQsMTE2MC42NCAxMTE4Ljg3LDExNzEuODcgMTEwNy4zMiwxMTc0LjE0QzEwOTUuNzcsMTE3Ni40MiAxMDg0LjU0LDExNjguODkgMTA4Mi4yNiwxMTU3LjM0TDEwNTQuODIsMTAxOC4yMUwxMDI3LjQ2LDk2NS43NThDMTAyMC4wOSw5NTEuNjQxIDEwMTIuNzIsOTM3LjUyNSAxMDA1LjM0LDkyMy40MDlMOTkwLjUyNiw4OTUuMTQxTDk5MC4xMjgsODk0LjgzQzk3MC45ODQsODc5LjEzMSA4ODEuMDEyLDgwNC41MjUgODE3LjkwNiw3NDkuNTM5QzgwOS4zMjksNzU2LjExOCA4MDAuMTY4LDc2MS44NiA3OTAuNTIxLDc2Ni42NjFDODY3LjI0NCw4MDEuMTAyIDkyMS45ODQsODkwLjYzMyA5MjEuOTg0LDk5NS41NDJDOTIxLjk4NCwxMTMwLjE5IDgzMS44MDMsMTIzOS41MiA3MjAuNzI1LDEyMzkuNTJDNjA5LjY0NywxMjM5LjUyIDUxOS40NjYsMTEzMC4xOSA1MTkuNDY2LDk5NS41NDJDNTE5LjQ2Niw4OTEuMzg4IDU3My40MjIsODAyLjM4OSA2NDkuMjczLDc2Ny40MTVDNjM4Ljg3NCw3NjIuMzc1IDYyOS4wMjgsNzU2LjI0NSA2MTkuODYzLDc0OS4xNjJDNTU2LjcyNiw4MDQuMTkyIDQ2Ni4zOTUsODc5LjA5NiA0NDcuMjA5LDg5NC44M0w0NDYuODExLDg5NS4xNDFMNDMxLjk5Myw5MjMuNDA5QzQyNC42MjEsOTM3LjUyNSA0MTcuMjQ5LDk1MS42NDEgNDA5Ljg3Nyw5NjUuNzU4TDM4Mi41MjIsMTAxOC4yMUwzNTUuMDc1LDExNTcuMzRDMzUyLjc5NiwxMTY4Ljg5IDM0MS41NjgsMTE3Ni40MiAzMzAuMDE2LDExNzQuMTRDMzE4LjQ2NSwxMTcxLjg3IDMxMC45MzcsMTE2MC42NCAzMTMuMjE1LDExNDkuMDlMMzQxLjI1NywxMDA2Ljk0QzM0MS42NSwxMDA0Ljk0IDM0Mi4zMjgsMTAwMy4wMSAzNDMuMjY4LDEwMDEuMjFDMzQzLjI2OCwxMDAxLjIxIDM5OC4xMzgsODk1LjkzIDQwOS4yMjYsODc0Ljk0MkM0MTIuMDk2LDg2OS41MSA0MTUuODU3LDg2Ni4xODEgNDE5Ljc4MSw4NjQuMjA0TDQxNy4wMTYsODY0LjM5NEM0MTcuMDE2LDg2NC4zOTQgNDE4LjQ1OSw4NjMuMjI4IDQyMC4xNTQsODYxLjgzN0M0MzguOTkxLDg0Ni4zOTEgNTI2Ljg2MSw3NzMuNTM1IDU4OS40NTMsNzE5LjA2OUM1ODAuMjY5LDcwNy40OTQgNTcyLjM5OSw2OTQuNjc2IDU2Ni4wOTMsNjgwLjg4NEM0ODIuODMzLDY4MS44MSAzNzAuMjIyLDY4MC40NTQgMzQ1Ljk4LDY4MC4wMjlMMzQ1LjQ4Myw2ODAuMDA5TDMxNS44ODQsNjkxLjk3OUMzMDEuMTIxLDY5Ny45NzYgMjg2LjM1OCw3MDMuOTczIDI3MS41OTUsNzA5Ljk3TDIxNi43NjgsNzMyLjI3OEwxMDUuNjc1LDgyMC43MjRDOTYuNDY0LDgyOC4wNTggODMuMDMxLDgyNi41MzMgNzUuNjk4LDgxNy4zMjJDNjguMzY0LDgwOC4xMTEgNjkuODg5LDc5NC42NzggNzkuMSw3ODcuMzQ1TDE5Mi41OTUsNjk2Ljk4NkMxOTQuMTg4LDY5NS43MTggMTk1Ljk1Myw2OTQuNjg1IDE5Ny44MzgsNjkzLjkxN0MxOTcuODM4LDY5My45MTcgMzA3Ljg1NCw2NDkuMTIxIDMyOS45LDY0MC4yODhDMzM1LjYxMyw2MzcuOTk5IDM0MC42NDEsNjM3Ljg5OCAzNDQuOTE2LDYzOC45MzhMMzQyLjY4Miw2MzcuMjg2QzM0Mi42ODIsNjM3LjI4NiAzNDQuNTM1LDYzNy4zMzEgMzQ2LjcyNyw2MzcuMzY5QzM2OS41Nyw2MzcuNzY5IDQ3MS40MDUsNjM5LjAwNiA1NTIuMzk3LDYzOC4zNDhDNTQ5Ljk1NCw2MjUuOTk1IDU0OC42NjgsNjEzLjE3OCA1NDguNjY4LDYwMC4wNDFDNTQ4LjY2OCw1OTguODEgNTQ4LjY4LDU5Ny41ODMgNTQ4LjcwMiw1OTYuMzY0QzQ4Ni4wOTgsNTQ0LjM0OSA0MDkuMDYzLDQ3Ny42OTMgMzkxLjgxMyw0NjIuNjA0TDM5MS40NCw0NjIuMjYyTDM2MS4xMTksNDUyLjI5N0MzNDUuOTgyLDQ0Ny4zNDkgMzMwLjg0NSw0NDIuNCAzMTUuNzA4LDQzNy40NTFMMjU5LjQ3LDQxOS4xTDExNy43MTYsNDE0LjkyQzEwNS45NDcsNDE0LjU3MyA5Ni42NzQsNDA0LjczNiA5Ny4wMjEsMzkyLjk2N0M5Ny4zNjgsMzgxLjE5OCAxMDcuMjA1LDM3MS45MjUgMTE4Ljk3NCwzNzIuMjcyTDI2My43OTcsMzc2LjU0MkMyNjUuODMxLDM3Ni42MDIgMjY3Ljg0NiwzNzYuOTUzIDI2OS43ODEsMzc3LjU4NEMyNjkuNzgxLDM3Ny41ODQgMzgyLjY1Myw0MTQuMzgyIDQwNS4xNzksNDIxLjg2NkM0MTEuMDEsNDIzLjgwMyA0MTQuOTEyLDQyNi45NjUgNDE3LjUwNyw0MzAuNTExTDQxNi44NjUsNDI3LjgxNUM0MTYuODY1LDQyNy44MTUgNDE4LjI1Myw0MjkuMDQ2IDQxOS45MDQsNDMwLjQ4OUM0MzUuMjU3LDQ0My45MTkgNDk4LjI4Miw0OTguNDcyIDU1NS45NTgsNTQ2Ljg0MUM1NTkuMzc5LDUzNC42ODUgNTYzLjk0Miw1MjMuMDYgNTY5LjUxMSw1MTIuMTE1Wk04NTUuMTA3LDg2OC45MDRMNzg4LjQzMiw5NzMuNjIzTDcyMS40NTIsOTMxLjAzNkw2NTQuNDMyLDk3My42MjNMNTg1LjkzMiw4NjkuNTk0QzU2NS4wMDIsOTA0LjkyNiA1NTIuNzU4LDk0OC42NjkgNTUyLjc1OCw5OTUuNTQyQzU1Mi43NTgsMTExMC4yNiA2MjYuMDk0LDEyMDYuMjIgNzIwLjcyNSwxMjA2LjIyQzgxNS4zNTYsMTIwNi4yMiA4ODguNjkyLDExMTAuMjYgODg4LjY5Miw5OTUuNTQyQzg4OC42OTIsOTQ4LjM3MSA4NzYuMjkyLDkwNC4zNyA4NTUuMTA3LDg2OC45MDRaIiBzdHlsZT0iZmlsbDp3aGl0ZTsiLz4KICAgIDwvZz4KPC9zdmc+Cg=='/>
            </div>
            <h3>
				<?php echo panel()->a()->title(); ?>
            </h3>
            <h5>
				<?php printf( __( "Version: %s", "demopress" ), panel()->a()->settings()->i()->version_full() ); ?>
            </h5>
        </div>

		<?php

		foreach ( panel()->sidebar_links as $group ) {
			if ( ! empty( $group ) ) {
				echo '<div class="d4p-links-group">';

				foreach ( $group as $link ) {
					echo '<a class="' . $link['class'] . '" href="' . $link['url'] . '">' . panel()->r()->icon( $link['icon'] ) . '<span>' . $link['label'] . '</span></a>';
				}

				echo '</div>';
			}
		}
	} else if ( demopress_gen()->is_idle() ) { ?>
        <div class="d4p-panel-scroller d4p-scroll-active">
            <div class="d4p-panel-title">
				<?php echo panel()->r()->icon( 'ui-sun' ); ?>
                <h3><?php _e( "Generator", "demopress" ); ?></h3>
				<?php echo '<h4>' . panel()->r()->icon( $_subpanels[ $_subpanel ]['icon'] ) . $_subpanels[ $_subpanel ]['title'] . '</h4>'; ?>
            </div>
            <div class="d4p-panel-info">
				<?php echo $_subpanels[ $_subpanel ]['info']; ?>
            </div>
            <div class="d4p-panel-buttons">
                <input type="submit" value="<?php _e( "Run Generator", "demopress" ); ?>" class="button-primary"/>
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php _e( "Return to top", "demopress" ); ?></a>
            </div>
        </div>
	<?php } else { ?>
        <div class="d4p-panel-scroller d4p-scroll-active">
            <div class="d4p-panel-title">
				<?php echo panel()->r()->icon( 'ui-sun' ); ?>
                <h3><?php _e( "Generator", "demopress" ); ?></h3>
                <h4><?php echo panel()->r()->icon( 'ui-play' ); ?><?php _e( "Status", "demopress" ) ?></h4>
            </div>
			<?php if ( demopress_gen()->is_running() ) { ?>
                <div class="d4p-panel-info">
					<?php _e( "The generator is currently running. You can use the button below to stop it. If you choose to stop it, you must know that the stop is not immediate, it can take up to 15 seconds for the running process to get the stop message.", "demopress" ) ?>
                </div>
                <div class="d4p-panel-buttons">
                    <a href="<?php echo wp_nonce_url( admin_url( 'options-general.php?page=demopress&panel=dashboard&demopress_handler=getback&single-action=stoptask' ), 'demopress-task-stop' ); ?>" style="text-align: center" class="button-secondary"><?php _e( "Stop Generator", "demopress" ); ?></a>
                </div>
			<?php } else if ( demopress_gen()->is_finished() || demopress_gen()->is_error() ) { ?>
                <div class="d4p-panel-info">
					<?php _e( "The generator is has finished the last task. Use the button below to reset the last task data.", "demopress" ) ?>
                </div>
                <div class="d4p-panel-buttons">
                    <a href="<?php echo wp_nonce_url( admin_url( 'options-general.php?page=demopress&panel=dashboard&demopress_handler=getback&single-action=resettask' ), 'demopress-task-reset' ); ?>" style="text-align: center" class="button-secondary"><?php _e( "Reset Generator", "demopress" ); ?></a>
                </div>
			<?php } ?>
        </div>
	<?php } ?>
</div>
