#!/usr/bin/env python
from cairo import *

border_width = 4	# Around edge of diagram
label_padding = 8	# Gap between bar and text
bar_width = 400		# Length of longest bar
title_font_size = 20	# Title font size
font_size = 14		# Label font size
gap_between_rows = 4	# Vertical gap between bars
bar_border_width = 1	# Outline stroke width for each bar

def plot(title, data):
	max_value = max(value for key, value in data)
	bar_scale_factor = float(bar_width) / max_value

	# Dummy surface for measuring...
	surface = ImageSurface(FORMAT_RGB24, 100, 100)
	cr = Context(surface)

	cr.set_font_size(title_font_size)
	title_font_ascent, title_font_descent, title_font_height, title_font_max_x_adv, title_font_max_y_adv = cr.font_extents()
	title_extents = cr.text_extents(title)
	title_left_start = title_extents[0]
	title_width = title_extents[2]
	title_height = title_extents[3] - title_extents[1]

	cr.set_font_size(font_size)
	ascent, descent, height, max_x_adv, max_y_adv = cr.font_extents()
	row_spacing = height + gap_between_rows

	class Bar:
		def __init__(self, (key, value)):
			self.key = key
			self.value = value
			self.length = max(1, value * bar_scale_factor)

			text_ext = cr.text_extents(self.key)
			self.key_width = text_ext[2]

			text_ext = cr.text_extents(str(self.value))
			self.value_label_width = text_ext[2]

	data = map(Bar, data)

	max_key_width = max(row.key_width for row in data)

	max_value_with_label_width = max(row.length + row.value_label_width for row in data) + label_padding

	# Real surface for drawing
	content_width = border_width * 2 + max(max_key_width + label_padding + max_value_with_label_width, title_width)
	surface = ImageSurface(FORMAT_RGB24,
				int(border_width * 2 + content_width),
				int(title_height + row_spacing * len(data) + border_width * 2))
	cr = Context(surface)

	# Set background colour
	cr.set_source_rgb(1, 1, 1)
	cr.paint()

	cr.translate(border_width, border_width)

	# Title
	title_x = (content_width - title_width) / 2
	#cr.rectangle(title_x, 0, title_width, title_height)
	#cr.set_source_rgb(1, 0, 0)
	#cr.stroke()

	cr.set_source_rgb(0, 0, 0)
	cr.set_font_size(title_font_size)
	cr.move_to(title_x, title_font_ascent)
	cr.show_text(title)

	cr.translate(0, title_height)

	cr.set_font_size(font_size)

	# Place (0, 0) at the top-left corner of the first bar
	cr.translate(max_key_width + label_padding, 0)
	for row in data:
		cr.set_source_rgb(0, 0, 0)

		cr.move_to(-row.key_width - label_padding, ascent)
		cr.show_text(row.key)

		cr.move_to(row.length + label_padding, ascent)
		cr.show_text(str(row.value))

		cr.rectangle(0, 0, row.length, height)
		cr.fill()

		if row.length > bar_border_width * 2:
			linear = LinearGradient(0, 0, bar_width, 0)
			linear.add_color_stop_rgb(0, 0, 0.3, 1)
			linear.add_color_stop_rgb(1, 1, 1, 1)
			cr.set_source(linear)
			cr.rectangle(bar_border_width, bar_border_width,
				     row.length - bar_border_width * 2,
				     height - bar_border_width * 2)
			cr.fill()

		cr.translate(0, row_spacing)

	return surface

if __name__ == '__main__':
	surface = plot('Test', [
		('Good', 5),
		('OK', 10),
		('Bad', 2),
		('Odd', 0),
	])
	surface.write_to_png('out.png')
