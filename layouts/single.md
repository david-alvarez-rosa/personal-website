{{- $content := .RenderShortcodes | replaceRE `(?s)<style>.*?</style>` "" | replaceRE `(?m)^&nbsp;` "" | replaceRE `<span class="sep"></span>` " · " | strings.TrimSpace -}}
{{- range $m := findRE `(?s)\\\\\(.+?\\\\\)|\\\\\[.+?\\\\\]` $content | uniq -}}
  {{- $tex := substr $m 3 -3 | replaceRE "\\\\([!-/:-@\\[-`{-~])" "$1" -}}
  {{- $math := cond (strings.HasPrefix $m `\\[`) (printf "$$%s$$" $tex) (printf "$%s$" (replaceRE `\s*\n\s*` " " $tex | strings.TrimSpace)) -}}
  {{- $content = replace $content $m $math -}}
{{- end -}}
{{- range $m := findRE `\]\((?:\./assets)?/images/[^)\s]+\)` $content | uniq -}}
  {{- $path := replaceRE `^\]\((?:\./assets/)?/?(images/[^)]+)\)$` "$1" $m -}}
  {{- with resources.Get $path -}}
    {{- $content = replace $content $m (printf "](%s)" .Permalink) -}}
  {{- end -}}
{{- end -}}
# {{ .Title }}
{{ with .Params.subtitle }}
> {{ . }}
{{ end }}
{{ with .Params.Author }}By {{ index . 0 }}, {{ end }}{{ $.Date.Format "2 January 2006" }}. Canonical: {{ .Permalink }}

{{ $content }}
