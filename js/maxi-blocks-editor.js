(function (wp) {
  if (typeof wp !== 'undefined') {
    wp.domReady(() => {
      wp.hooks.addFilter(
        'blocks.getSaveContent.extraProps',
        'maxi-blocks/modify-image-urls',
        (extraProps, blockType, attributes) => {
          if (
            blockType.name !== 'core/image'
            && blockType.name !== 'core/gallery'
            && blockType.name !== 'maxi-blocks/image-maxi'
          ) {
            return extraProps;
          }

          const newAttributes = { ...attributes };

          const replaceUrls = (url) => url
            .replace(
              'https://stagingdemo.maxiblocks.com/wp-content/uploads',
              'https://img.maxiblocks.com',
            )
            .replace(
              'https://maxiblocks.com/demo/wp-content/uploads',
              'https://img.maxiblocks.com',
            )
            .replace(
              'https://site-editor-demo.maxiblocks.com/wp-content/uploads',
              'https://img.maxiblocks.com',
            );

          if (newAttributes.url) {
            newAttributes.url = replaceUrls(newAttributes.url);
          }

          if (newAttributes['dc-media-url']) {
            newAttributes['dc-media-url'] = replaceUrls(newAttributes['dc-media-url']);
          }

          return { ...extraProps, ...newAttributes };
        },
      );
    });
  }
}(window.wp));
