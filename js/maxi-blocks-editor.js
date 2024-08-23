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

      if (attributes.url) {
        attributes.url = attributes.url
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
      }

      if (attributes['dc-media-url']) {
        attributes['dc-media-url'] = attributes['dc-media-url']
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
      }

      return extraProps;
    },
  );
});
