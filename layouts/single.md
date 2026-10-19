{{- $content := .RenderShortcodes | replaceRE `(?s)<style>.*?</style>` "" | replaceRE `(?m)^&nbsp;` "" | replaceRE `<span class="sep"></span>` " · " | strings.TrimSpace -}}
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
