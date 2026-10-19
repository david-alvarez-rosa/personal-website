{{- $src := strings.TrimPrefix "./assets/" (.Get "src") | strings.TrimPrefix "/" -}}
{{- $alt := path.BaseName $src | replaceRE `-` " " | strings.FirstUpper -}}
{{- $link := printf "![%s](%s)" $alt (.Get "src") -}}
{{- with resources.Get $src -}}
  {{- $link = printf "![%s](%s)" $alt .Permalink -}}
  {{- if strings.HasSuffix $src ".gif" -}}
    {{- with resources.Get (replaceRE `\.gif$` ".mp4" $src) }}{{ $link = printf "[Video: %s](%s)" $alt (. | fingerprint).Permalink }}{{ end -}}
  {{- end -}}
{{- end -}}
{{ $link }}
{{- with .Get "caption" }}

{{ replaceRE `<[^>]+>` "" . }}
{{- end -}}
