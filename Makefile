INSTALL_DIR=../..
OBJECTS=cookbook/addlink-tags2 \
cookbook/addlink-tags2/LICENSE.txt \
cookbook/addlink-tags2/README.markdown \
cookbook/addlink-tags2.php


dist: $(OBJECTS)
	zip --recurse-paths addlink-tags2.zip ${OBJECTS} --exclude \*~ \.DS_Store

clean:
	find . -name '*~' -delete

