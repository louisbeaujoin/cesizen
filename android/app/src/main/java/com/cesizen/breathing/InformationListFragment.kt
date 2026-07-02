package com.cesizen.breathing

import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import com.cesizen.breathing.databinding.FragmentInformationBinding
import com.cesizen.breathing.databinding.ItemInformationBinding

class InformationListFragment : Fragment() {

    private var _binding: FragmentInformationBinding? = null
    private val binding get() = _binding!!

    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?, savedInstanceState: Bundle?): View {
        _binding = FragmentInformationBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        loadPages()
    }

    override fun onResume() {
        super.onResume()
        loadPages()
    }

    private fun loadPages() {
        binding.loadingText.visibility = View.GONE
        binding.emptyText.visibility = View.GONE
        binding.infoList.removeAllViews()

        val localPages = LocalDataManager.getPublishedPages(requireContext())
        if (localPages.isNotEmpty()) {
            renderPages(localPages.map { p ->
                ApiInfoPage(p.id, p.title, p.slug, p.content)
            })
        } else {
            binding.emptyText.visibility = View.VISIBLE
        }
    }

    private fun renderPages(pages: List<ApiInfoPage>) {
        if (_binding == null) return
        val inflater = LayoutInflater.from(requireContext())
        pages.forEach { page ->
            val item = ItemInformationBinding.inflate(inflater, binding.infoList, false)
            item.infoTitle.text = page.title
            item.root.setOnClickListener {
                val intent = Intent(requireContext(), InformationDetailActivity::class.java).apply {
                    putExtra(InformationDetailActivity.EXTRA_SLUG, page.slug)
                    putExtra(InformationDetailActivity.EXTRA_TITLE, page.title)
                    putExtra(InformationDetailActivity.EXTRA_CONTENT, page.content)
                }
                startActivity(intent)
            }
            binding.infoList.addView(item.root)
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}
