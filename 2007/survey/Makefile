all_charts:
	rm -f charts/*.png
	rm -f charts/template.html
	./histogram.py AllResults.csv 1
	./histogram.py AllResults.csv 2 'str(int(x)/10)' '"%s0-%s9" % (x, x)'
	./bar.py       AllResults.csv 3 10
	./bar.py       AllResults.csv 11 14
	./other.py     AllResults.csv 15
	./histogram.py AllResults.csv 16
	./other.py     AllResults.csv 17
	./bar.py       AllResults.csv 18 25
	./other.py     AllResults.csv 26
	./bar.py       AllResults.csv 27 29
	./other.py     AllResults.csv 30
	./other.py     AllResults.csv 31
	./histogram.py AllResults.csv 32
	./histogram.py AllResults.csv 33
	./histogram.py AllResults.csv 34
	./histogram.py AllResults.csv 35
	./histogram.py AllResults.csv 36
	./bar.py       AllResults.csv 37 41
	./other.py     AllResults.csv 42
	./other.py     AllResults.csv 43 long
	./bar.py       AllResults.csv 44 48
	./other.py     AllResults.csv 49
	./histogram.py AllResults.csv 50
	./bar.py       AllResults.csv 51 55
	./other.py     AllResults.csv 56
	./histogram.py AllResults.csv 57
	./histogram.py AllResults.csv 58
	./histogram.py AllResults.csv 59
	./histogram.py AllResults.csv 60
	./histogram.py AllResults.csv 61
	./histogram.py AllResults.csv 62
	./bar.py       AllResults.csv 63 67
	./other.py     AllResults.csv 68
	./histogram.py AllResults.csv 69
	./other.py     AllResults.csv 70 long
	./other.py     AllResults.csv 71 long
	./other.py     AllResults.csv 72 long
	./other.py     AllResults.csv 73 long
	./histogram.py AllResults.csv 74
	echo Packagers...
	# Note: we have three fewer columns here!
	#./histogram.py AllInterestedPackagers.csv 71
	./bar.py       AllInterestedPackagers.csv 72 75
	./bar.py       AllInterestedPackagers.csv 76 80
	./other.py     AllInterestedPackagers.csv 81
	./bar.py       AllInterestedPackagers.csv 82 89
	./other.py     AllInterestedPackagers.csv 90
	./other.py     AllInterestedPackagers.csv 91
	./other.py     AllInterestedPackagers.csv 92 long
	./bar.py       AllInterestedPackagers.csv 93 96
	./other.py     AllInterestedPackagers.csv 97
	./bar.py       AllInterestedPackagers.csv 98 101
	./other.py     AllInterestedPackagers.csv 102
	./histogram.py AllInterestedPackagers.csv 103
	./other.py     AllResults.csv             107 long
