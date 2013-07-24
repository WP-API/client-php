<?php

use JsonSchema\Uri\UriResolver;
use JsonSchema\Uri\Retrievers\PredefinedArray;

/**
 * URI retrieved based on a predefined array of schemas
 *
 * This is based on the PredefinedArray retriever, but allows relative refs.
 */
class WPAPI_Test_SchemaRetriever extends PredefinedArray {
	public function retrieve($uri)
	{
		$resolver = new UriResolver();
		$parts = $resolver->parse($uri);
		$parts['fragment'] = '#';
		$uri = $resolver->generate($parts);

		return parent::retrieve($uri);
	}
}
