INSTALL_DIR=../..
OBJECTS=cookbook/addlink-tags2/LICENSE.txt cookbook/addlink-tags2/README.markdown cookbook/addlink-tags2.php

dist: $(OBJECTS)
	zip -r addlink-tags2.zip cookbook

clean:
	find . -name '*~' -delete

install: addlink-tags2.zip
	unzip addlink-tags2.zip -d $(INSTALL_DIR)