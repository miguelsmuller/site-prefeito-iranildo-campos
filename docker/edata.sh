#!/usr/bin/env sh

echo "== Export =="
chown www-data:www-data -R ./wp-content

wp cli info --allow-root  > /dev/null 2>&1

wp db export database.sql --tables="${WORDPRESS_TABLE_PREFIX}commentmeta,${WORDPRESS_TABLE_PREFIX}comments,${WORDPRESS_TABLE_PREFIX}links,${WORDPRESS_TABLE_PREFIX}options,${WORDPRESS_TABLE_PREFIX}postmeta,${WORDPRESS_TABLE_PREFIX}posts,${WORDPRESS_TABLE_PREFIX}termmeta,${WORDPRESS_TABLE_PREFIX}terms,${WORDPRESS_TABLE_PREFIX}term_relationships,${WORDPRESS_TABLE_PREFIX}term_taxonomy" \
  --quiet \
  --allow-root

gzip -f database.sql
mv database.sql.gz /docker/data

tar -zcf /docker/data/media.tgz ./wp-content/uploads

echo "== Done! =="
